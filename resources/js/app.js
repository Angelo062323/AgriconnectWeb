import './bootstrap';

// Admin layout behaviours (dark mode + sidebar toggle)
document.addEventListener('DOMContentLoaded', () => {
	const body = document.body;
	if (!body.classList.contains('admin-body')) return;

	// --- Dark mode toggle ---
	const themeToggle = document.getElementById('theme-toggle');
	const THEME_KEY = 'agriconnect-theme';

	if (themeToggle) {
		const savedTheme = window.localStorage.getItem(THEME_KEY);
		if (savedTheme === 'dark') {
			body.classList.add('theme-dark');
		}

		const updateThemeIcon = () => {
			const icon = themeToggle.querySelector('.theme-toggle-icon');
			if (!icon) return;
			icon.textContent = body.classList.contains('theme-dark') ? '☀️' : '🌙';
		};

		updateThemeIcon();

		themeToggle.addEventListener('click', () => {
			body.classList.toggle('theme-dark');
			const isDark = body.classList.contains('theme-dark');
			window.localStorage.setItem(THEME_KEY, isDark ? 'dark' : 'light');
			updateThemeIcon();
		});
	}

	// --- Sidebar collapse toggle ---
	const layout = document.querySelector('.admin-layout');
	const sidebarToggle = document.getElementById('sidebar-toggle');
	const SIDEBAR_KEY = 'agriconnect-sidebar';

	if (layout && sidebarToggle) {
		const savedSidebar = window.localStorage.getItem(SIDEBAR_KEY);
		if (savedSidebar === 'collapsed') {
			layout.classList.add('sidebar-collapsed');
		}

		const updateSidebarIcon = () => {
			sidebarToggle.textContent = layout.classList.contains('sidebar-collapsed') ? '☰' : '☰';
		};

		updateSidebarIcon();

		sidebarToggle.addEventListener('click', () => {
			layout.classList.toggle('sidebar-collapsed');
			const collapsed = layout.classList.contains('sidebar-collapsed');
			window.localStorage.setItem(SIDEBAR_KEY, collapsed ? 'collapsed' : 'expanded');
			updateSidebarIcon();
		});
	}

	// --- LGU modal & table actions ---
	const lguModal = document.getElementById('lgu-modal');
	const lguNewBtn = document.getElementById('lgu-new-btn');
	const lguForm = document.getElementById('lgu-form');
	const lguMethodInput = document.getElementById('lgu_form_method');
	const lguIdField = document.getElementById('lgu_id_field');
	const lguTitle = document.getElementById('lgu-modal-title');
	const lguSubmitBtn = document.getElementById('lgu-submit-btn');
	const lguEditBtn = document.getElementById('lgu-edit-selected');
	const lguTableForm = document.getElementById('lgu-table-form');
	const lguSelectAll = document.getElementById('lgu-select-all');

	const openModal = (modal) => {
		if (modal) modal.classList.add('open');
	};

	const closeModal = (modal) => {
		if (modal) modal.classList.remove('open');
	};

	if (lguModal && lguForm) {
		const lguStoreUrl = lguForm.dataset.storeUrl;

		const resetLguFormForCreate = () => {
			lguForm.action = lguStoreUrl;
			if (lguMethodInput) lguMethodInput.value = 'POST';
			if (lguIdField) lguIdField.value = '';
			if (lguTitle) lguTitle.textContent = 'New LGU';
			if (lguSubmitBtn) lguSubmitBtn.textContent = 'Save';
		};

		if (lguNewBtn) {
			lguNewBtn.addEventListener('click', () => {
				resetLguFormForCreate();
				openModal(lguModal);
			});
		}

		lguModal.querySelectorAll('[data-modal-close="lgu-modal"]').forEach((el) => {
			el.addEventListener('click', () => closeModal(lguModal));
		});

		if (lguSelectAll && lguTableForm) {
			lguSelectAll.addEventListener('change', () => {
				lguTableForm.querySelectorAll('.lgu-row-checkbox').forEach((cb) => {
					cb.checked = lguSelectAll.checked;
				});
			});
		}

		if (lguEditBtn && lguTableForm) {
			lguEditBtn.addEventListener('click', () => {
				const checked = lguTableForm.querySelectorAll('.lgu-row-checkbox:checked');
				if (checked.length === 0) {
					alert('Select a record to edit.');
					return;
				}
				if (checked.length > 1) {
					alert('Please select only one record to edit.');
					return;
				}
				const row = checked[0].closest('tr');
				if (!row) return;
				const dataset = row.dataset;
				if (dataset.updateUrl) lguForm.action = dataset.updateUrl;
				if (lguMethodInput) lguMethodInput.value = 'PUT';
				if (lguIdField) lguIdField.value = dataset.id || '';

				const nameInput = document.getElementById('lgu_name');
				const municipalitySelect = document.getElementById('municipality');
				const provinceInput = document.getElementById('province');
				const emailInput = document.getElementById('contact_email');

				if (nameInput) nameInput.value = dataset.lguName || '';
				if (provinceInput) provinceInput.value = dataset.province || '';
				if (emailInput) emailInput.value = dataset.contactEmail || '';
				if (municipalitySelect) {
					municipalitySelect.value = dataset.municipality || '';
				}

				if (lguTitle) lguTitle.textContent = 'Edit LGU';
				if (lguSubmitBtn) lguSubmitBtn.textContent = 'Update';
				openModal(lguModal);
			});
		}
	}

	// --- Farmer modal & table actions ---
	const farmerModal = document.getElementById('farmer-modal');
	const farmerNewBtn = document.getElementById('farmer-new-btn');
	const farmerForm = document.getElementById('farmer-form');
	const farmerMethodInput = document.getElementById('farmer_form_method');
	const farmerIdField = document.getElementById('farmer_id_field');
	const farmerTitle = document.getElementById('farmer-modal-title');
	const farmerSubmitBtn = document.getElementById('farmer-submit-btn');
	const farmerEditBtn = document.getElementById('farmer-edit-selected');
	const farmerTableForm = document.getElementById('farmer-table-form');
	const farmerSelectAll = document.getElementById('farmer-select-all');
	const rsbsaInput = document.getElementById('rsbsa_number');
	const farmerLguSelect = document.getElementById('lgu_id');
	const farmerMunicipalitySelect = document.getElementById('municipality');

	// Auto-format RSBSA number as 00-00-00-000-000000 while typing
	if (rsbsaInput) {
		const formatRsbsa = (value) => {
			// keep digits only, up to 15 digits
			const digits = (value || '').replace(/\D/g, '').slice(0, 15);
			let result = '';
			if (digits.length > 0) {
				result = digits.slice(0, 2);
			}
			if (digits.length > 2) {
				result += '-' + digits.slice(2, 4);
			}
			if (digits.length > 4) {
				result += '-' + digits.slice(4, 6);
			}
			if (digits.length > 6) {
				result += '-' + digits.slice(6, 9);
			}
			if (digits.length > 9) {
				result += '-' + digits.slice(9, 15);
			}
			return result;
		};

		// Format any pre-filled value (e.g., from old() or edit)
		rsbsaInput.value = formatRsbsa(rsbsaInput.value);

		rsbsaInput.addEventListener('input', (e) => {
			const cursorPos = e.target.selectionStart;
			const beforeLength = e.target.value.length;
			e.target.value = formatRsbsa(e.target.value);
			const afterLength = e.target.value.length;
			const diff = afterLength - beforeLength;
			// try to keep cursor near where the user was typing
			e.target.selectionStart = e.target.selectionEnd = Math.max(0, cursorPos + diff);
		});
	}

	// Auto-select municipality when an LGU is chosen
	if (farmerLguSelect && farmerMunicipalitySelect) {
		farmerLguSelect.addEventListener('change', () => {
			const option = farmerLguSelect.options[farmerLguSelect.selectedIndex];
			if (!option) return;
			const muni = option.getAttribute('data-municipality');
			if (!muni) return;
			farmerMunicipalitySelect.value = muni;
		});
	}

	if (farmerModal && farmerForm) {
		const farmerStoreUrl = farmerForm.dataset.storeUrl;

		const resetFarmerFormForCreate = () => {
			farmerForm.action = farmerStoreUrl;
			if (farmerMethodInput) farmerMethodInput.value = 'POST';
			if (farmerIdField) farmerIdField.value = '';
			if (farmerTitle) farmerTitle.textContent = 'Register Farmer';
			if (farmerSubmitBtn) farmerSubmitBtn.textContent = 'Save';
		};

		if (farmerNewBtn) {
			farmerNewBtn.addEventListener('click', () => {
				resetFarmerFormForCreate();
				openModal(farmerModal);
			});
		}

		farmerModal.querySelectorAll('[data-modal-close="farmer-modal"]').forEach((el) => {
			el.addEventListener('click', () => closeModal(farmerModal));
		});

		if (farmerSelectAll && farmerTableForm) {
			farmerSelectAll.addEventListener('change', () => {
				farmerTableForm.querySelectorAll('.farmer-row-checkbox').forEach((cb) => {
					cb.checked = farmerSelectAll.checked;
				});
			});
		}

		if (farmerEditBtn && farmerTableForm) {
			farmerEditBtn.addEventListener('click', () => {
				const checked = farmerTableForm.querySelectorAll('.farmer-row-checkbox:checked');
				if (checked.length === 0) {
					alert('Select a record to edit.');
					return;
				}
				if (checked.length > 1) {
					alert('Please select only one record to edit.');
					return;
				}
				const row = checked[0].closest('tr');
				if (!row) return;
				const dataset = row.dataset;
				if (dataset.updateUrl) farmerForm.action = dataset.updateUrl;
				if (farmerMethodInput) farmerMethodInput.value = 'PUT';
				if (farmerIdField) farmerIdField.value = dataset.id || '';

				const setValue = (id, value) => {
					const el = document.getElementById(id);
					if (el) el.value = value ?? '';
				};

				setValue('lgu_id', dataset.lguId);
				setValue('rsbsa_number', dataset.rsbsa);
				setValue('first_name', dataset.firstName);
				setValue('last_name', dataset.lastName);
				setValue('contact_number', dataset.contactNumber);
				setValue('email', dataset.email);
				setValue('crop_type', dataset.cropType);
				setValue('farm_location', dataset.farmLocation);
				setValue('barangay', dataset.barangay);
				setValue('municipality', dataset.municipality);
				setValue('province', dataset.province);
				setValue('latitude', dataset.latitude);
				setValue('longitude', dataset.longitude);

				if (farmerTitle) farmerTitle.textContent = 'Edit Farmer';
				if (farmerSubmitBtn) farmerSubmitBtn.textContent = 'Update';
				openModal(farmerModal);
			});
		}
	}
});
