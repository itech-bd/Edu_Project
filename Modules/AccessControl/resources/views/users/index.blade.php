<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			{{ __('All Users') }}
		</h2>
	</x-slot>

	@push('styles')
		<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
		<style>
			/* Fix: the select arrow overlaps the numbers in the length dropdown */
			.dataTables_wrapper .dataTables_length select {
				padding-right: 2.0rem !important;
				padding-left: 0.75rem !important;
				background-position: right 0.5rem center !important;
				background-repeat: no-repeat !important;
			}
		</style>
	@endpush

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900">
					<div class="flex items-center justify-between mb-4">
						<div>
							@if (session('success'))
								<div class="text-sm text-green-600">
									{{ session('success') }}
								</div>
							@endif
						</div>
					</div>

					<div class="border-b border-gray-200 mb-4">
						<nav class="-mb-px flex gap-6" aria-label="Tabs">
							<button
								type="button"
								data-users-tab="student"
								class="users-tab-btn whitespace-nowrap border-b-2 border-indigo-600 px-1 pb-3 text-sm font-medium text-indigo-600"
							>
								Students
							</button>
							<button
								type="button"
								data-users-tab="mentor"
								class="users-tab-btn whitespace-nowrap border-b-2 border-transparent px-1 pb-3 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
							>
								Mentors
							</button>
							<button
								type="button"
								data-users-tab="admin"
								class="users-tab-btn whitespace-nowrap border-b-2 border-transparent px-1 pb-3 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
							>
								Admins
							</button>
						</nav>
					</div>

					<div id="users-tab-student" class="users-tab-panel">
						<div class="w-full overflow-x-auto">
							<table id="users-table-student" class="w-full min-w-max divide-y divide-gray-200">
							<thead class="bg-gray-50">
								<tr>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SL</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Date</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
									<th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
								</tr>
							</thead>
							<tbody class="bg-white divide-y divide-gray-200"></tbody>
							</table>
						</div>
					</div>

					<div id="users-tab-mentor" class="users-tab-panel hidden">
						<div class="w-full overflow-x-auto">
							<table id="users-table-mentor" class="w-full min-w-max divide-y divide-gray-200">
							<thead class="bg-gray-50">
								<tr>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SL</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Date</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
									<th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
								</tr>
							</thead>
							<tbody class="bg-white divide-y divide-gray-200"></tbody>
							</table>
						</div>
					</div>

					<div id="users-tab-admin" class="users-tab-panel hidden">
						<div class="w-full overflow-x-auto">
							<table id="users-table-admin" class="w-full min-w-max divide-y divide-gray-200">
							<thead class="bg-gray-50">
								<tr>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SL</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Date</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
									<th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
								</tr>
							</thead>
							<tbody class="bg-white divide-y divide-gray-200"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	@push('scripts')
		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
		<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
		<script>
			$(function () {
				const ajaxUrl = @json(route('users.index'));
				const tableByRole = {};

				function initUsersTable(role) {
					if (tableByRole[role]) {
						return tableByRole[role];
					}

					const tableId = '#users-table-' + role;

					tableByRole[role] = $(tableId).DataTable({
						processing: true,
						serverSide: true,
						autoWidth: false,
							scrollX: true,
							scrollCollapse: true,
						ajax: {
							url: ajaxUrl,
							data: function (d) {
								d.role = role;
							}
						},
						columns: [
							{
								data: 'DT_RowIndex',
								name: 'DT_RowIndex',
								orderable: false,
								searchable: false,
								className: 'px-6 py-4 whitespace-nowrap'
							},
							{
								data: 'name',
								name: 'name',
								className: 'px-6 py-4 whitespace-nowrap'
							},
							{
								data: 'email',
								name: 'email',
								className: 'px-6 py-4 whitespace-nowrap'
							},
							{
								data: 'registration_date',
								name: 'created_at',
								className: 'px-6 py-4 whitespace-nowrap'
							},
							{
								data: 'roles',
								name: 'roles.name',
								orderable: false,
								searchable: false,
								className: 'px-6 py-4 whitespace-normal'
							},
							{
								data: 'actions',
								name: 'actions',
								orderable: false,
								searchable: false,
								className: 'px-6 py-4 whitespace-nowrap text-right'
							},
						],
						order: [[3, 'desc']],
					});

					return tableByRole[role];
				}

				function setActiveTab(role) {
					$('.users-tab-panel').addClass('hidden');
					$('#users-tab-' + role).removeClass('hidden');

					$('.users-tab-btn')
						.removeClass('border-indigo-600 text-indigo-600')
						.addClass('border-transparent text-gray-500');

					$('[data-users-tab="' + role + '"]')
						.removeClass('border-transparent text-gray-500')
						.addClass('border-indigo-600 text-indigo-600');

					const table = initUsersTable(role);
					setTimeout(function () {
						table.columns.adjust();
					}, 0);
				}

				$(document).on('click', '.users-tab-btn', function () {
					const role = $(this).data('users-tab');
					setActiveTab(role);
				});

				setActiveTab('student');
			});
		</script>
	@endpush
</x-app-layout>
