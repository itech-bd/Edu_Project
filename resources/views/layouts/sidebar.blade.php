@php
    $user = Auth::user();
    $isAdmin = $user && method_exists($user, 'hasRole') ? $user->hasRole('admin') : false;
    $isMentor = $user && method_exists($user, 'hasRole') ? $user->hasRole('mentor') : false;
    $isStudent = $user && method_exists($user, 'hasRole') ? $user->hasRole('student') : false;

    $canManageCourses = $user
        && (
            $user->can('addCourse')
            || $user->can('editCourse')
            || $user->can('deleteCourse')
        );

    $canSeeMentorBatches = $user
        && $user->can('readBatch')
        && $user->can('addClassSchedule')
        && ! $user->can('addBatch');

    $canSeeStudentPanel = $user
        && $user->can('readBatch')
        && $user->can('readCourse')
        && ! $user->can('addBatch')
        && ! $user->can('addClassSchedule');

    $canManageReviews = $user
        && (
            $user->can('addReview')
            || $user->can('editReview')
            || $user->can('deleteReview')
        );

    $linkBase = 'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition';
    $active = 'bg-indigo-600 text-white shadow-sm';
    $inactive = 'text-slate-300 hover:bg-slate-800 hover:text-white';
@endphp

<nav class="space-y-1">
    <a href="/dashboard"
        @click="sidebarOpen = false"
        class="{{ $linkBase }} {{ request()->routeIs('dashboard') ? $active : $inactive }}">
        <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M10.707 1.707a1 1 0 00-1.414 0l-7 7A1 1 0 003 10h1v7a1 1 0 001 1h4a1 1 0 001-1v-4h2v4a1 1 0 001 1h4a1 1 0 001-1v-7h1a1 1 0 00.707-1.707l-7-7z" />
        </svg>
        <span>Dashboard</span>
    </a>

    <a href="/profile"
        @click="sidebarOpen = false"
        class="{{ $linkBase }} {{ request()->routeIs('profile.*') ? $active : $inactive }}">
        <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 2a4 4 0 100 8 4 4 0 000-8z" clip-rule="evenodd" />
            <path fill-rule="evenodd" d="M.458 16.944A10 10 0 0110 12c3.59 0 6.73 1.89 8.542 4.944A1 1 0 0117.66 18H2.34a1 1 0 01-1.882-1.056z" clip-rule="evenodd" />
        </svg>
        <span>Profile</span>
    </a>

    @if($isAdmin || $canManageCourses)
        <a href="/dashboard/courses"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('dashboard.courses.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M3 4a1 1 0 011-1h10a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4z" />
                <path d="M7 6h6v2H7V6zm0 4h6v2H7v-2z" />
            </svg>
            <span>Courses</span>
        </a>
    @endif

    @if($isAdmin || $canManageReviews || ($user && $user->can('readReview')))
        <a href="/dashboard/reviews"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('dashboard.reviews.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.71c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 0 0 .951-.69l1.07-3.292Z" />
            </svg>
            <span>Reviews</span>
        </a>
    @endif

    @if($isAdmin)
        <a href="/dashboard/batches"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('dashboard.batches.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M4 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V6.414a1 1 0 00-.293-.707l-2.414-2.414A1 1 0 0015.586 3H4z" />
                <path d="M6 8h8v2H6V8zm0 4h8v2H6v-2z" />
            </svg>
            <span>Batches</span>
        </a>

        <a href="/dashboard/admin/invoices"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('dashboard.admin.invoices.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M4 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V6.414a1 1 0 00-.293-.707l-2.414-2.414A1 1 0 0015.586 3H4z" />
                <path d="M6 8h8v2H6V8zm0 4h8v2H6v-2z" />
            </svg>
            <span>Invoices</span>
        </a>

        <a href="{{ route('dashboard.contact-messages.index') }}"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('dashboard.contact-messages.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M2.94 6.34A2 2 0 014.6 5h10.8a2 2 0 011.66 1.34L10 10.88 2.94 6.34ZM2 8.15V14a2 2 0 002 2h12a2 2 0 002-2V8.15l-7.46 4.8a1 1 0 01-1.08 0L2 8.15Z" clip-rule="evenodd" />
            </svg>
            <span>Contact Messages</span>
        </a>
    @endif

    @if($isMentor || $canSeeMentorBatches)
        <a href="/dashboard/mentor/batches"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('dashboard.mentor.batches.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M4 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V6.414a1 1 0 00-.293-.707l-2.414-2.414A1 1 0 0015.586 3H4z" />
                <path d="M6 8h8v2H6V8zm0 4h8v2H6v-2z" />
            </svg>
            <span>My Batches</span>
        </a>
    @endif

    @if($isStudent || $canSeeStudentPanel)
        <a href="/dashboard/student/courses"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('dashboard.student.courses.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M3 4a1 1 0 011-1h10a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4z" />
                <path d="M7 6h6v2H7V6zm0 4h6v2H7v-2z" />
            </svg>
            <span>My Courses</span>
        </a>

        <a href="/dashboard/student/batches"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('dashboard.student.batches.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M4 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V6.414a1 1 0 00-.293-.707l-2.414-2.414A1 1 0 0015.586 3H4z" />
                <path d="M6 8h8v2H6V8zm0 4h8v2H6v-2z" />
            </svg>
            <span>My Batches</span>
        </a>

        <a href="/dashboard/student/mentors"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('dashboard.student.mentors.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 2a4 4 0 100 8 4 4 0 000-8z" clip-rule="evenodd" />
                <path fill-rule="evenodd" d="M.458 16.944A10 10 0 0110 12c3.59 0 6.73 1.89 8.542 4.944A1 1 0 0117.66 18H2.34a1 1 0 01-1.882-1.056z" clip-rule="evenodd" />
            </svg>
            <span>My Mentors</span>
        </a>

        <a href="/dashboard/student/invoices"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('dashboard.student.invoices.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M4 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V6.414a1 1 0 00-.293-.707l-2.414-2.414A1 1 0 0015.586 3H4z" />
                <path d="M6 8h8v2H6V8zm0 4h8v2H6v-2z" />
            </svg>
            <span>Invoices</span>
        </a>
    @endif

    @if($isAdmin)
        <a href="/users"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('users.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M10 2a4 4 0 100 8 4 4 0 000-8z" />
                <path fill-rule="evenodd" d="M.458 16.944A10 10 0 0110 12c3.59 0 6.73 1.89 8.542 4.944A1 1 0 0117.66 18H2.34a1 1 0 01-1.882-1.056z" clip-rule="evenodd" />
            </svg>
            <span>Users</span>
        </a>

        <a href="/admin/frontend-editor"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('admin.frontend-editor.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M4 2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6.5a1 1 0 1 0 0-2H4V4h12v6.5a1 1 0 1 0 2 0V4a2 2 0 0 0-2-2H4z" />
                <path d="M12.293 10.293a1 1 0 0 1 1.414 0l3 3a1 1 0 0 1 .242.39l1 3a1 1 0 0 1-1.265 1.265l-3-1a1 1 0 0 1-.39-.242l-3-3a1 1 0 0 1 0-1.414l2-2z" />
            </svg>
            <span>Frontend Editor</span>
        </a>

        <a href="{{ route('dashboard.admin.news.index') }}"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('dashboard.admin.news.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M4 3a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V6.414a1 1 0 0 0-.293-.707l-2.414-2.414A1 1 0 0 0 13.586 3H4z" />
                <path d="M6 8h8v2H6V8zm0 4h8v2H6v-2z" />
            </svg>
            <span>News &amp; Updates</span>
        </a>
    @endif

    @if($user && $user->can('readMentor'))
        @if(! ($isStudent || $canSeeStudentPanel))
        <a href="/dashboard/mentors"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('dashboard.mentors.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M10 2 1.5 6 10 10l8.5-4L10 2Z" />
                <path d="M4 9.2V13c0 .6.4 1.2 1 1.5 1.4.8 3.2 1.5 5 1.5s3.6-.7 5-1.5c.6-.3 1-.9 1-1.5V9.2L10 12 4 9.2Z" />
                <path d="M18.5 6.5v6.5a1 1 0 0 1-2 0V7.4l2-.9Z" />
            </svg>
            <span>Mentors</span>
        </a>
        @endif
    @endif

    @if($isAdmin)
        <a href="/roles"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('roles.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5 2a3 3 0 00-3 3v2a3 3 0 003 3h2a3 3 0 003-3V5a3 3 0 00-3-3H5zm0 10a3 3 0 00-3 3v1a2 2 0 002 2h3a3 3 0 003-3v-1a2 2 0 00-2-2H5zm8-10a2 2 0 00-2 2v3a3 3 0 003 3h1a2 2 0 002-2V5a3 3 0 00-3-3h-1zm1 10a3 3 0 00-3 3v1a2 2 0 002 2h1a3 3 0 003-3v-1a2 2 0 00-2-2h-1z" clip-rule="evenodd" />
            </svg>
            <span>Roles</span>
        </a>

        <a href="/permissions"
            @click="sidebarOpen = false"
            class="{{ $linkBase }} {{ request()->routeIs('permissions.*') ? $active : $inactive }}">
            <svg class="h-5 w-5 shrink-0 opacity-90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 1a1 1 0 01.894.553l1.5 3A1 1 0 0013.289 5h3.211a1 1 0 01.707 1.707l-2.5 2.5a1 1 0 00-.277.894l.75 3.75a1 1 0 01-1.451 1.07L10 13.347l-3.479 1.574a1 1 0 01-1.45-1.07l.75-3.75a1 1 0 00-.278-.894l-2.5-2.5A1 1 0 013.5 5h3.211a1 1 0 00.895-.447l1.5-3A1 1 0 0110 1z" clip-rule="evenodd" />
            </svg>
            <span>Permissions</span>
        </a>
    @endif
</nav>
