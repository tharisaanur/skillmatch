document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | FOTO PROFIL
    |--------------------------------------------------------------------------
    */

    const photoInput = document.getElementById('profile_photo');
    const profilePreview = document.getElementById('profilePreview');
    const deletePhoto = document.getElementById('deletePhoto');
    const removePhotoInput = document.getElementById('remove_profile_photo');

    if (photoInput && profilePreview) {
        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            if (removePhotoInput) {
                removePhotoInput.value = '0';
            }

            const reader = new FileReader();
            reader.onload = function (event) {
                profilePreview.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS FOTO PROFIL
    |--------------------------------------------------------------------------
    */

    if (deletePhoto && profilePreview && removePhotoInput) {
        deletePhoto.addEventListener('click', function () {
            const defaultAvatar = profilePreview.dataset.defaultAvatar;
            profilePreview.src = defaultAvatar;

            if (photoInput) {
                photoInput.value = '';
            }

            removePhotoInput.value = '1';
        });
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS PENCARIAN TIM
    |--------------------------------------------------------------------------
    */

    const teamStatus = document.getElementById('team_status');
    const statusWrapper = document.getElementById('statusWrapper');

    if (teamStatus && statusWrapper) {
        function updateStatusColor() {
            statusWrapper.classList.remove('status-active', 'status-inactive');

            if (teamStatus.value === 'inactive') {
                statusWrapper.classList.add('status-inactive');
            } else {
                statusWrapper.classList.add('status-active');
            }
        }

        updateStatusColor();
        teamStatus.addEventListener('change', updateStatusColor);
    }

    /*
    |--------------------------------------------------------------------------
    | PERUBAHAN BELUM DISIMPAN
    |--------------------------------------------------------------------------
    */

    const profileForm = document.querySelector('[data-profile-form] form');
    const unsavedDialog = document.getElementById('unsavedDialog');
    let dirty = false;

    if (profileForm && unsavedDialog) {
        const markDirty = () => { dirty = true; };
        
        profileForm.querySelectorAll('input, select, textarea').forEach((field) => {
            field.addEventListener('input', markDirty);
            field.addEventListener('change', markDirty);
        });
        
        profileForm.addEventListener('submit', () => { dirty = false; });

        const closeUnsaved = () => { unsavedDialog.hidden = true; };
        
        document.querySelectorAll('[data-close-unsaved]').forEach((button) => {
            button.addEventListener('click', closeUnsaved);
        });
        
        document.getElementById('saveBeforeLeave')?.addEventListener('click', () => {
            profileForm.requestSubmit();
        });

        document.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', (event) => {
                // Khusus tombol Kelola Keahlian Utama: Matikan status dirty lalu izinkan berpindah halaman
                if (link.classList.contains('skill-link')) {
                    dirty = false;
                    return;
                }

                if (
                    !dirty || 
                    !link.href || 
                    link.target === '_blank' || 
                    link.href.includes('#')
                ) {
                    return;
                }

                event.preventDefault();
                unsavedDialog.hidden = false;
            });
        });

        window.addEventListener('beforeunload', (event) => {
            if (!dirty) return;
            event.preventDefault();
            event.returnValue = '';
        });
    }
});