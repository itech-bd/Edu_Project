<?php

namespace App\Http\Controllers;

use App\Models\FrontendPage;
use App\Models\FrontendSection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Modules\Course\Models\Course;
use Modules\Mentors\Models\Mentor;
use Modules\Reviews\Models\Review;
use Modules\NewsUpdates\Models\NewsUpdate;
use Yajra\DataTables\Facades\DataTables;

/**
 * Public site controller.
 *
 * @category Controller
 * @package  App\Http\Controllers
 * @author   Unknown <unknown@example.invalid>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://laravel.com
 */
class SiteController extends Controller
{
    /**
     * Load CMS page + sections for a given slug.
     *
     * @param string $slug Page slug.
     *
     * @return array{
     *     cmsPage: FrontendPage,
     *     cmsSections: Collection<int, FrontendSection>,
     *     cmsSectionsByKey: Collection<string, FrontendSection>
     * }
     */
    protected function loadCms(string $slug): array
    {
        $hasPagesTable = Schema::hasTable('frontend_pages');
        $hasSectionsTable = Schema::hasTable('frontend_sections');

        if (! $hasPagesTable || ! $hasSectionsTable) {
            $cmsPage = new FrontendPage(['slug' => $slug]);
            $cmsSections = new Collection();

            /**
             * Empty keyed sections collection.
             *
             * @var Collection<string, FrontendSection> $cmsSectionsByKey
             */
            $cmsSectionsByKey = new Collection();

            return compact('cmsPage', 'cmsSections', 'cmsSectionsByKey');
        }

        $cmsPage = FrontendPage::query()->firstOrCreate(['slug' => $slug]);

        $cmsSections = FrontendSection::query()
            ->where('frontend_page_id', $cmsPage->id)
            ->active()
            ->orderBy('section_key')
            ->get();

        /**
         * Keyed sections collection.
         *
         * @var Collection<string, FrontendSection> $cmsSectionsByKey
         */
        $cmsSectionsByKey = $cmsSections->keyBy('section_key');

        return compact('cmsPage', 'cmsSections', 'cmsSectionsByKey');
    }

    /**
     * Show the home page.
     *
     * @return View
     */
    public function home(): View
    {
        $cms = $this->loadCms('home');

        $latestNews = new Collection();
        if (Schema::hasTable('news_updates')) {
            $latestNews = NewsUpdate::query()
                ->published()
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->limit(3)
                ->get(['id', 'title', 'slug', 'excerpt', 'published_at', 'created_at']);
        }

        $mentors = Mentor::query()
            ->with(['user:id,name,profile_image'])
            ->where('is_active', true)
            ->orderByDesc('id')
            ->limit(12)
            ->get(['id', 'user_id', 'name', 'topic', 'bio']);

        return view('welcome', array_merge($cms, compact('mentors', 'latestNews')));
    }

    /**
     * Public News listing page (uses Yajra DataTables).
     */
    public function news(): View
    {
        $cms = $this->loadCms('news');

        return view('pages.news', $cms);
    }

    /**
     * DataTables JSON for public news page.
     */
    public function newsData()
    {
        abort_unless(Schema::hasTable('news_updates'), 404);

        $query = NewsUpdate::query()
            ->published()
            ->select(['id', 'title', 'slug', 'excerpt', 'published_at', 'created_at'])
            ->orderByDesc('published_at')
            ->orderByDesc('id');

        return DataTables::eloquent($query)
            ->addColumn('date', function (NewsUpdate $item) {
                $dt = $item->published_at ?: $item->created_at;
                return $dt ? $dt->format('d M Y') : '';
            })
            ->addColumn('actions', function (NewsUpdate $item) {
                return route('news.show', $item);
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    /**
     * Public News details page.
     */
    public function newsShow(NewsUpdate $newsUpdate): View
    {
        abort_unless($newsUpdate->status === 'published', 404);

        return view('pages.news-show', compact('newsUpdate'));
    }

    /**
     * Show the mentors page.
     *
     * @return View
     */
    public function mentors(): View
    {
        $cms = $this->loadCms('mentors');

        $mentors = Mentor::query()
            ->with(['user:id,name,profile_image'])
            ->where('is_active', true)
            ->orderByDesc('id')
            ->paginate(12);

        return view('pages.mentors', array_merge($cms, compact('mentors')));
    }

    /**
     * Show a public mentor profile page.
     */
    public function mentorShow(string $mentor): View|RedirectResponse
    {
        if (ctype_digit($mentor)) {
            $legacyMentor = Mentor::query()->findOrFail((int) $mentor);

            if (is_string($legacyMentor->slug) && $legacyMentor->slug !== '') {
                return redirect()->route('mentors.show', ['mentor' => $legacyMentor->slug], 301);
            }

            $mentor = (string) $legacyMentor->id;
        }

        $mentorQuery = Mentor::query()->where('slug', $mentor);

        if (ctype_digit($mentor)) {
            $mentorQuery->orWhereKey((int) $mentor);
        }

        $mentor = $mentorQuery->firstOrFail();

        abort_unless($mentor->is_active, 404);

        $mentor->loadMissing(
            [
                'user' => fn ($query) => $query
                    ->select(['id', 'name', 'email', 'profile_image'])
                    ->with(
                        [
                            'profile',
                            'address',
                            'educations' => fn ($q) => $q
                                ->orderByDesc('end_year')
                                ->orderByDesc('start_year')
                                ->orderByDesc('id'),
                            'experiences' => fn ($q) => $q
                                ->orderByDesc('end_date')
                                ->orderByDesc('start_date')
                                ->orderByDesc('id'),
                            'skills' => fn ($q) => $q->orderBy('name'),
                        ]
                    ),
            ]
        );

        return view('pages.mentor-show', compact('mentor'));
    }

    /**
     * Show a generic CMS-driven page.
     *
     * @param string $slug Page slug.
     *
     * @return View
     */
    public function page(string $slug): View
    {
        $cms = $this->loadCms($slug);

        if ($slug === 'courses') {
            $courses = Course::query()
                ->where('status', 'active')
                ->orderByDesc('id')
                ->paginate(12);

            return view('pages.' . $slug, array_merge($cms, compact('courses')));
        }

        if ($slug === 'reviews') {
            $reviews = new Collection();

            if (Schema::hasTable('reviews')) {
                $reviews = Review::query()
                    ->where('status', 'active')
                    ->orderBy('sort_order')
                    ->orderByDesc('id')
                    ->limit(48)
                    ->get(['id', 'name', 'designation', 'quote', 'rating']);
            }

            return view('pages.' . $slug, array_merge($cms, compact('reviews')));
        }

        if ($slug === 'news') {
            // Keep backward-compatibility: route now points to SiteController@news.
            return view('pages.news', $cms);
        }

        return view('pages.' . $slug, $cms);
    }

    /**
     * Show a public single course details page.
     *
     * @param Course $course Course model (route model binding).
     *
     * @return View
     */
    public function course(Course $course): View
    {
        abort_unless($course->status === 'active', 404);

        $course->load(
            [
                'batches' => function ($query) {
                    $query
                        ->whereIn('status', ['upcoming', 'running'])
                        ->orderBy('start_date');
                },
            ]
        );

        return view('pages.course-show', compact('course'));
    }
}
