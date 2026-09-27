<style>
    .sm-page,
    .sm-page *,
    .sm-modal,
    .sm-modal * {
        box-sizing: border-box;
    }

    .sm-page {
        min-height: calc(100vh - var(--header-height, 80px));
        padding: 48px 24px 80px;
        color: #fff;
        background:
            linear-gradient(
                120deg,
                rgba(21, 53, 75, .75),
                rgba(48, 91, 117, .65)
            ),
            url("{{ asset('images/bg.png') }}") center / cover no-repeat;
    }

    .sm-wrap {
        width: 100%;
        max-width: 1180px;
        margin: auto;
    }

    .sm-banner,
    .sm-card,
    .sm-detail {
        border: 1px solid rgba(208, 240, 249, .75);
        background: linear-gradient(
            125deg,
            rgba(177, 222, 237, .22),
            rgba(33, 78, 102, .28),
            rgba(164, 216, 232, .20)
        );
    }

    .sm-banner {
        padding: 30px 26px;
        border-radius: 36px;
    }

    .sm-banner h1 {
        max-width: 850px;
        margin: 0 0 20px;
        color: #fff;
        font-size: clamp(28px, 3.6vw, 46px);
        line-height: 1.2;
    }

    .sm-banner p {
        max-width: 740px;
        margin: 0;
        font-size: clamp(16px, 1.8vw, 22px);
        line-height: 1.45;
    }

    .sm-search {
        display: flex;
        width: min(100%, 490px);
        min-height: 58px;
        margin: 24px auto 32px;
        padding: 6px 12px 6px 20px;
        border: 1px solid rgba(213, 245, 252, .65);
        border-radius: 20px;
        background: rgba(210, 235, 248, .17);
    }

    .sm-search input {
        width: 100%;
        min-width: 0;
        border: 0;
        background: transparent;
        color: #fff;
        font: inherit;
        font-size: 19px;
    }

    .sm-search input::placeholder {
        color: #d0dfe7;
    }

    .sm-search button {
        width: 44px;
        flex-shrink: 0;
        border: 0;
        background: transparent;
        color: #fff;
        font-size: 22px;
        cursor: pointer;
    }

    .sm-heading {
        display: flex;
        align-items: end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
    }

    .sm-heading p {
        margin: 0 0 6px;
        color: #b0edf9;
        font-size: 13px;
        font-weight: 700;
    }

    .sm-heading h2 {
        margin: 0;
        font-size: clamp(25px, 3vw, 34px);
    }

    .sm-count {
        color: #d3edf5;
        font-size: 14px;
    }

    .sm-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 28px;
    }

    .sm-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        padding: 22px 20px 18px;
        border-radius: 28px;
    }

    .sm-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 18px;
    }

    .sm-status {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        white-space: nowrap;
        font-size: 13px;
    }

    .sm-status::before {
        content: "";
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #83bd50;
    }

    .sm-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .sm-card-top .sm-tags {
        justify-content: flex-end;
    }

    .sm-tags span {
        padding: 3px 9px;
        border-radius: 20px;
        background: #b0edf9;
        color: #073c53;
        font-size: 11px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .sm-summary {
        display: flex;
        flex: 1;
        flex-direction: column;
        min-width: 0;
    }

    .sm-summary h3 {
        margin: 0 0 14px;
        color: #fff;
        font-size: 19px;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .sm-description {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
        line-clamp: 3;
        min-height: 63px;
        margin: 0 0 16px;
        overflow: hidden;
        color: #edf6fa;
        font-size: 14px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .sm-owner {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: auto 0 18px;
        font-size: 14px;
        overflow-wrap: anywhere;
    }

    .sm-owner img,
    .sm-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        flex-shrink: 0;
        border-radius: 50%;
        object-fit: cover;
        background: #b0edf9;
        color: #073c53;
        font-weight: 800;
    }

    .sm-match {
        margin-bottom: 18px;
    }

    .sm-match > p {
        margin: 0 0 8px;
        font-size: 14px;
    }

    .sm-match-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sm-track {
        flex: 1;
        height: 12px;
        min-width: 0;
        border-radius: 20px;
        background: #052b3b;
        overflow: hidden;
    }

    .sm-track span {
        display: block;
        height: 100%;
        background: #b0edf9;
    }

    .sm-match-row small {
        color: #e3f2f7;
        font-size: 11px;
    }

    .sm-meta {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 12px;
    }

    .sm-meta span {
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .sm-meta i {
        font-size: 20px;
    }

    .sm-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-top: 18px;
    }

    .sm-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 8px 12px;
        border: 1px solid #d5f5fc;
        border-radius: 9px;
        background: rgba(176, 237, 249, .10);
        color: #fff;
        font-family: inherit;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.3;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
    }

    .sm-button:hover:not(:disabled) {
        background: #b0edf9;
        color: #073c53;
    }

    .sm-button:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    .sm-primary {
        background: #b0edf9;
        color: #073c53;
    }

    .sm-dark {
        background: #04344c;
    }

    .sm-feedback,
    .sm-empty {
        padding: 18px;
        border: 1px solid #b0edf9;
        border-radius: 14px;
        background: rgba(4, 52, 76, .75);
    }

    .sm-feedback {
        margin-bottom: 20px;
    }

    .sm-feedback p {
        margin: 5px 0;
    }

    .sm-empty {
        grid-column: 1 / -1;
        padding: 40px 20px;
        text-align: center;
    }

    /* POPUP — BERLAKU PADA SEMUA UKURAN LAYAR */

    dialog.sm-modal {
        position: fixed;
        inset: 0;
        width: min(500px, calc(100vw - 28px));
        max-width: none;
        max-height: calc(100dvh - 32px);
        margin: auto;
        padding: 24px 16px 30px;
        overflow-y: auto;
        border: 1px solid #b8d8e4;
        border-radius: 28px;
        background: linear-gradient(
            130deg,
            rgba(90, 136, 150, .95),
            rgba(14, 48, 64, .95) 45%,
            rgba(90, 136, 150, .95)
        );
        color: #fff;
        font-family: inherit;
    }

    dialog.sm-modal:not([open]) {
        display: none;
    }

    dialog.sm-modal::backdrop {
        background: rgba(0, 13, 21, .74);
    }

    .sm-modal-header {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        margin-bottom: 20px;
        padding: 0 42px;
    }

    .sm-modal-header h2 {
        margin: 0;
        color: #fff;
        font-size: 19px;
        text-align: center;
    }

    .sm-close {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 35px;
        height: 35px;
        padding: 0;
        border: 2px solid #e2f4fa;
        border-radius: 50%;
        background: transparent;
        color: #fff;
        font-size: 28px;
        line-height: 1;
        cursor: pointer;
    }

    .sm-confirm-card {
        padding: 20px 18px;
        border: 1px solid #a5b535;
        border-radius: 26px;
        background: rgba(182, 218, 231, .13);
    }

    .sm-confirm-card .sm-description {
        display: block;
        min-height: auto;
        max-height: 160px;
        overflow-y: auto;
        margin-bottom: 24px;
    }

    .sm-confirm-card .sm-owner {
        margin-top: 0;
    }

    .sm-confirm-card .sm-owner img,
    .sm-confirm-card .sm-avatar {
        width: 46px;
        height: 46px;
    }

    .sm-modal-actions {
        gap: 40px;
        padding: 0 18px;
        margin-top: 24px;
    }

    .sm-modal-actions form {
        margin: 0;
    }

    .sm-modal-actions .sm-button {
        width: 100%;
        min-height: 46px;
    }

    /* DETAIL */

    .sm-detail-page {
        background: linear-gradient(110deg, #04344c, #12485e, #33768e);
    }

    .sm-detail {
        display: grid;
        grid-template-columns: minmax(0, 1.65fr) minmax(0, 1fr);
        grid-template-areas:
            "title side"
            "intro side"
            "description side"
            "actions actions";
        gap: 28px 36px;
        min-height: 560px;
        padding: 28px 24px;
        border-radius: 30px;
    }

    .sm-detail > * {
        min-width: 0;
    }

    .sm-detail-title {
        grid-area: title;
        margin: 0;
        font-size: 30px;
        overflow-wrap: anywhere;
    }

    .sm-detail-intro {
        grid-area: intro;
        display: grid;
        grid-template-columns: 245px minmax(0, 1fr);
        gap: 32px;
        align-items: center;
    }

    .sm-owner-panel {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 220px;
        padding: 20px;
        border: 1px solid #c4e6f0;
        border-radius: 28px;
        background: rgba(166, 218, 233, .13);
        text-align: center;
    }

    .sm-owner-panel img,
    .sm-owner-panel .sm-avatar {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        font-size: 50px;
    }

    .sm-owner-panel p {
        margin: 12px 0 0;
        overflow-wrap: anywhere;
    }

    .sm-milestones h2,
    .sm-detail-description h2 {
        margin: 0 0 12px;
        font-size: 19px;
    }

    .sm-milestones ul {
        margin: 0;
        padding-left: 22px;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .sm-detail-description {
        grid-area: description;
        padding-top: 20px;
    }

    .sm-detail-description p {
        margin: 0;
        font-size: 22px;
        line-height: 1.4;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .sm-detail-side {
        grid-area: side;
        display: flex;
        flex-direction: column;
        gap: 36px;
        padding-top: 20px;
    }

    .sm-deadline {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
    }

    .sm-detail-side h2 {
        margin: 0 0 10px;
        font-size: 23px;
    }

    .sm-detail-side .sm-tags {
        margin-bottom: 14px;
    }

    .sm-detail-side .sm-tags span {
        border: 1px solid #b0edf9;
        border-radius: 9px;
        background: #06364c;
        color: #b0edf9;
        font-size: 13px;
    }

    .sm-team h3 {
        margin: 0 0 16px;
        text-align: center;
        font-size: 15px;
    }

    .sm-team-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 9px;
    }

    .sm-person {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 9px;
        min-height: 96px;
        padding: 10px 6px;
        border: 1px solid #c4e6f0;
        border-radius: 26px;
        background: rgba(175, 223, 237, .14);
        text-align: center;
    }

    .sm-person img,
    .sm-person .sm-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .sm-person span:last-child {
        font-size: 11px;
        overflow-wrap: anywhere;
    }

    .sm-plus {
        height: 40px;
        font-size: 54px;
        line-height: 36px;
    }

    .sm-detail-actions {
        grid-area: actions;
        display: flex;
        justify-content: flex-end;
        align-items: end;
        flex-wrap: wrap;
        gap: 50px;
    }

    .sm-detail-actions form {
        margin: 0;
    }

    .sm-detail-actions .sm-button {
        min-width: 170px;
        min-height: 46px;
    }

    .sm-page a:focus-visible,
    .sm-page button:focus-visible,
    .sm-page input:focus-visible,
    .sm-modal button:focus-visible,
    .sm-modal a:focus-visible {
        outline: 3px solid #b0edf9;
        outline-offset: 3px;
    }

    @media (max-width: 1000px) {
        .sm-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .sm-detail {
            grid-template-columns: 1fr;
            grid-template-areas:
                "title"
                "intro"
                "description"
                "side"
                "actions";
        }

        .sm-team-grid {
            grid-template-columns: repeat(auto-fit, minmax(95px, 110px));
        }
    }

    @media (max-width: 600px) {
        .sm-page {
            padding: 28px 14px 50px;
        }

        .sm-grid {
            grid-template-columns: 1fr;
        }

        .sm-banner,
        .sm-detail {
            padding: 24px 18px;
        }

        .sm-detail-intro {
            grid-template-columns: 1fr;
        }

        .sm-owner-panel {
            width: 245px;
            max-width: 100%;
            justify-self: center;
        }

        .sm-detail-description p {
            font-size: 18px;
        }

        .sm-detail-actions {
            display: grid;
            gap: 12px;
        }

        .sm-detail-actions .sm-button {
            width: 100%;
        }

        .sm-modal-actions {
            gap: 14px;
            padding: 0;
        }

        .sm-modal-header h2 {
            font-size: 16px;
        }

        .sm-close {
            left: 0;
        }
    }
/* =====================================================
   FIX NAVBAR KHUSUS HALAMAN JELAJAHI PROJEK
===================================================== */

.site-header .nav-shell {
    display: flex !important;
    align-items: center !important;
}


.site-header .main-nav {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-end !important;

    flex-direction: row !important;
    flex-wrap: nowrap !important;

    gap: 28px;
}


/* =========================================
   BAGIAN KANAN
========================================= */

.site-header .navbar-actions {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    flex-direction: row !important;
    flex-wrap: nowrap !important;

    gap: 16px !important;

    width: auto !important;
    height: 44px !important;

    margin: 0 !important;
    padding: 0 !important;

    position: relative !important;

    top: auto !important;
    right: auto !important;
    bottom: auto !important;
    left: auto !important;

    transform: none !important;

    flex-shrink: 0 !important;
}


/* =========================================
   LONCENG
========================================= */

.site-header
.main-nav
.navbar-actions
a.navbar-notification {

    position: relative !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    flex: 0 0 42px !important;

    width: 42px !important;
    height: 42px !important;

    min-width: 42px !important;
    min-height: 42px !important;

    max-width: 42px !important;
    max-height: 42px !important;

    margin: 0 !important;
    padding: 0 !important;

    border: none !important;
    border-radius: 50% !important;

    background: transparent !important;

    color: #ffffff !important;

    line-height: 1 !important;

    top: auto !important;
    right: auto !important;
    bottom: auto !important;
    left: auto !important;

    transform: none !important;

    overflow: visible !important;
}


/* =========================================
   SVG LONCENG
========================================= */

.site-header
.main-nav
.navbar-actions
a.navbar-notification
svg {

    display: block !important;

    width: 22px !important;
    height: 22px !important;

    min-width: 22px !important;
    min-height: 22px !important;

    max-width: 22px !important;
    max-height: 22px !important;

    margin: 0 !important;
    padding: 0 !important;

    position: static !important;

    inset: auto !important;

    transform: none !important;

    flex: 0 0 22px !important;
}


/* =========================================
   BADGE ANGKA NOTIFIKASI
========================================= */

.site-header .navbar-notification-count {

    position: absolute !important;

    top: 0 !important;
    right: 0 !important;

    min-width: 17px !important;
    width: auto !important;
    height: 17px !important;

    padding: 0 4px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    border-radius: 999px !important;

    font-size: 9px !important;
    line-height: 1 !important;

    transform: none !important;
}


/* =========================================
   PROFILE
========================================= */

.site-header
.navbar-actions
.nav-profile {

    position: relative !important;

    display: flex !important;
    align-items: center !important;

    width: auto !important;
    height: 44px !important;

    margin: 0 !important;
    padding: 0 !important;

    top: auto !important;
    right: auto !important;
    bottom: auto !important;
    left: auto !important;

    transform: none !important;

    flex-shrink: 0 !important;
}


/* =========================================
   PROFILE BUTTON
========================================= */

.site-header
.navbar-actions
.profile-trigger {

    position: relative !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    flex-direction: row !important;

    gap: 10px !important;

    width: auto !important;
    height: 44px !important;

    margin: 0 !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;

    top: auto !important;
    right: auto !important;
    bottom: auto !important;
    left: auto !important;

    transform: none !important;
}


/* FOTO PROFILE */
.site-header
.navbar-actions
.profile-avatar-img {

    width: 40px !important;
    height: 40px !important;

    min-width: 40px !important;
    min-height: 40px !important;

    max-width: 40px !important;
    max-height: 40px !important;

    margin: 0 !important;

    border-radius: 50% !important;

    object-fit: cover !important;

    flex-shrink: 0 !important;
}


/* NAMA USER */
.site-header
.navbar-actions
.profile-name {

    display: inline-flex !important;
    align-items: center !important;

    width: auto !important;
    height: auto !important;

    margin: 0 !important;

    line-height: 1.2 !important;

    white-space: nowrap !important;
}


/* CHEVRON */
.site-header
.navbar-actions
.profile-chevron {

    width: 12px !important;
    height: 12px !important;

    min-width: 12px !important;
    min-height: 12px !important;

    max-width: 12px !important;
    max-height: 12px !important;

    margin: 0 !important;

    position: static !important;

    transform: none !important;

    flex-shrink: 0 !important;
}
</style>