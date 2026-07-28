<style id="permata-design-system">
    :root {
        --pi-bg: #f7f8fa;
        --pi-surface: #ffffff;
        --pi-surface-soft: #f9fafb;
        --pi-text: #101828;
        --pi-muted: #667085;
        --pi-quiet: #98a2b3;
        --pi-line: #e4e7ec;
        --pi-blue: #2878f0;
        --pi-blue-hover: #1768dc;
        --pi-blue-soft: #eef5ff;
        --pi-green: #12a06a;
        --pi-red: #e5484d;
        --pi-amber: #e59a16;
        --pi-radius: 10px;
        --pi-sidebar: 226px;
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    html,
    body {
        max-width: 100%;
        min-height: 100%;
    }

    body {
        margin: 0 !important;
        color: var(--pi-text) !important;
        background: var(--pi-bg) !important;
        font-family: "Inter", system-ui, sans-serif !important;
        font-size: 13px !important;
        line-height: 1.5 !important;
        -webkit-font-smoothing: antialiased;
    }

    /* Application shell */
    .app-sidebar {
        position: fixed !important;
        inset: 0 auto 0 0 !important;
        z-index: 1000 !important;
        display: flex !important;
        flex-direction: column !important;
        width: var(--pi-sidebar) !important;
        height: 100vh !important;
        padding: 14px 11px 10px !important;
        overflow: hidden !important;
        color: var(--pi-text) !important;
        background: #fbfcfd !important;
        border-right: 1px solid var(--pi-line) !important;
        box-shadow: none !important;
    }

    .app-sidebar-head {
        flex: 0 0 auto !important;
    }

    .app-brand {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        min-height: 42px !important;
        padding: 0 7px !important;
        color: var(--pi-text) !important;
        text-decoration: none !important;
    }

    .app-brand img {
        width: 34px !important;
        height: 34px !important;
        border-radius: 50% !important;
        object-fit: cover !important;
    }

    .app-brand span,
    .app-brand strong,
    .app-brand small {
        display: block !important;
    }

    .app-brand strong {
        font-size: 12px !important;
        font-weight: 700 !important;
        letter-spacing: -.02em !important;
    }

    .app-brand small {
        margin-top: 2px !important;
        color: var(--pi-muted) !important;
        font-size: 10px !important;
        font-weight: 500 !important;
    }

    .app-nav-search {
        position: relative !important;
        display: block !important;
        margin: 15px 2px 4px !important;
    }

    .app-nav-search > i {
        position: absolute !important;
        top: 50% !important;
        left: 11px !important;
        z-index: 1 !important;
        color: var(--pi-quiet) !important;
        font-size: 11px !important;
        transform: translateY(-50%) !important;
        pointer-events: none !important;
    }

    .app-nav-search input {
        width: 100% !important;
        height: 35px !important;
        margin: 0 !important;
        padding: 0 34px 0 31px !important;
        color: var(--pi-text) !important;
        background: var(--pi-surface) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: 7px !important;
        outline: none !important;
        box-shadow: none !important;
        font-family: inherit !important;
        font-size: 11px !important;
    }

    .app-nav-search input:focus {
        border-color: #b9cff7 !important;
        box-shadow: 0 0 0 3px rgba(40, 120, 240, .08) !important;
    }

    .app-nav-search input::placeholder {
        color: var(--pi-quiet) !important;
    }

    .app-nav-search kbd {
        position: absolute !important;
        top: 50% !important;
        right: 9px !important;
        display: grid !important;
        place-items: center !important;
        width: 17px !important;
        height: 17px !important;
        padding: 0 !important;
        color: var(--pi-quiet) !important;
        background: #f2f4f7 !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: 4px !important;
        font-family: inherit !important;
        font-size: 9px !important;
        font-weight: 600 !important;
        transform: translateY(-50%) !important;
        pointer-events: none !important;
    }

    .app-nav-scroll {
        flex: 1 1 auto !important;
        min-height: 0 !important;
        margin-top: 3px !important;
        padding: 0 2px 8px 0 !important;
        overflow-x: hidden !important;
        overflow-y: auto !important;
        scrollbar-color: #d0d5dd transparent !important;
        scrollbar-width: thin !important;
    }

    .app-nav-scroll::-webkit-scrollbar {
        width: 4px !important;
    }

    .app-nav-scroll::-webkit-scrollbar-thumb {
        background: #d0d5dd !important;
        border-radius: 999px !important;
    }

    .app-nav-label {
        margin: 16px 9px 6px !important;
        color: var(--pi-quiet) !important;
        font-size: 9px !important;
        font-weight: 650 !important;
        letter-spacing: .06em !important;
        text-transform: uppercase !important;
    }

    .app-nav {
        display: grid !important;
        gap: 2px !important;
    }

    .app-nav-link,
    .app-nav-group > summary {
        display: grid !important;
        grid-template-columns: 20px 1fr auto !important;
        align-items: center !important;
        gap: 8px !important;
        min-height: 36px !important;
        margin: 0 !important;
        padding: 0 10px !important;
        color: #475467 !important;
        background: transparent !important;
        border: 0 !important;
        border-radius: 7px !important;
        font-size: 11.5px !important;
        font-weight: 500 !important;
        line-height: 1 !important;
        text-decoration: none !important;
        cursor: pointer !important;
    }

    .app-nav-link i,
    .app-nav-group > summary i {
        width: 18px !important;
        color: #667085 !important;
        font-size: 13px !important;
        text-align: center !important;
    }

    .app-nav-link:hover,
    .app-nav-link.is-active,
    .app-nav-group > summary:hover {
        color: var(--pi-text) !important;
        background: #f0f2f5 !important;
    }

    .app-nav-link.is-active {
        font-weight: 650 !important;
        box-shadow: inset 2px 0 var(--pi-blue) !important;
    }

    .app-nav-link.is-active i {
        color: var(--pi-blue) !important;
    }

    .app-nav-group {
        margin: 0 !important;
        padding: 0 !important;
        border: 0 !important;
    }

    .app-nav-group > summary {
        list-style: none !important;
    }

    .app-nav-group > summary::-webkit-details-marker {
        display: none !important;
    }

    .app-nav-group > summary .fa-chevron-down {
        width: auto !important;
        font-size: 9px !important;
        transition: transform .15s ease !important;
    }

    .app-nav-group[open] > summary .fa-chevron-down {
        transform: rotate(180deg) !important;
    }

    .app-nav-group > div {
        display: grid !important;
        gap: 2px !important;
        padding: 2px 0 6px 36px !important;
    }

    .app-nav-group > div a {
        display: grid !important;
        grid-template-columns: 14px minmax(0, 1fr) !important;
        align-items: center !important;
        gap: 7px !important;
        min-height: 28px !important;
        padding: 6px 8px !important;
        overflow: hidden !important;
        color: var(--pi-muted) !important;
        border-radius: 6px !important;
        font-size: 11px !important;
        line-height: 1.35 !important;
        text-decoration: none !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }

    .app-nav-group > div a > i {
        width: 14px !important;
        color: var(--pi-quiet) !important;
        font-size: 9px !important;
        text-align: center !important;
    }

    .app-nav-group > div a > span {
        min-width: 0 !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }

    .app-nav-group > div a:hover,
    .app-nav-group > div a.is-active {
        color: var(--pi-blue) !important;
        background: var(--pi-blue-soft) !important;
    }

    .app-nav-group > div a:hover > i,
    .app-nav-group > div a.is-active > i {
        color: var(--pi-blue) !important;
    }

    .app-report-group > .app-report-panel {
        gap: 1px !important;
        padding: 3px 0 6px 35px !important;
    }

    .app-nav-subgroup {
        margin: 0 !important;
        padding: 0 !important;
        border: 0 !important;
    }

    .app-nav-subgroup > summary {
        display: grid !important;
        grid-template-columns: 1fr auto !important;
        align-items: center !important;
        min-height: 29px !important;
        padding: 5px 8px !important;
        color: var(--pi-muted) !important;
        border-radius: 6px !important;
        font-size: 10.5px !important;
        font-weight: 600 !important;
        line-height: 1.3 !important;
        list-style: none !important;
        cursor: pointer !important;
    }

    .app-nav-subgroup > summary::-webkit-details-marker {
        display: none !important;
    }

    .app-nav-subgroup > summary:hover {
        color: var(--pi-text) !important;
        background: #f0f2f5 !important;
    }

    .app-nav-subgroup > summary i {
        width: auto !important;
        color: var(--pi-quiet) !important;
        font-size: 8px !important;
        transition: transform .15s ease !important;
    }

    .app-nav-subgroup[open] > summary i {
        transform: rotate(180deg) !important;
    }

    .app-nav-subgroup > div {
        display: grid !important;
        gap: 1px !important;
        padding: 0 0 3px 7px !important;
    }

    .app-nav-subgroup > div a {
        min-height: 26px !important;
        padding: 5px 7px !important;
        font-size: 10px !important;
    }

    .app-nav-empty {
        margin: 20px 8px !important;
        color: var(--pi-quiet) !important;
        font-size: 10px !important;
        text-align: center !important;
    }

    .app-sidebar-footer {
        flex: 0 0 auto !important;
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) auto !important;
        align-items: center !important;
        gap: 5px !important;
        padding: 10px 3px 0 !important;
        border-top: 1px solid var(--pi-line) !important;
    }

    .app-profile-card {
        display: grid !important;
        grid-template-columns: 32px minmax(0, 1fr) !important;
        align-items: center !important;
        gap: 8px !important;
        min-width: 0 !important;
        padding: 5px !important;
        color: var(--pi-text) !important;
        border-radius: 7px !important;
        text-decoration: none !important;
    }

    .app-profile-card:hover,
    .app-profile-card.is-active {
        background: #f0f2f5 !important;
    }

    .app-profile-copy {
        min-width: 0 !important;
    }

    .app-profile-copy strong,
    .app-profile-copy small {
        display: block !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }

    .app-profile-copy strong {
        font-size: 10.5px !important;
        font-weight: 650 !important;
    }

    .app-profile-copy small {
        margin-top: 1px !important;
        color: var(--pi-quiet) !important;
        font-size: 9px !important;
    }

    .app-sidebar-footer form {
        margin: 0 !important;
    }

    .app-sidebar-logout {
        display: grid !important;
        place-items: center !important;
        width: 30px !important;
        height: 30px !important;
        padding: 0 !important;
        color: var(--pi-muted) !important;
        background: transparent !important;
        border: 0 !important;
        border-radius: 7px !important;
        box-shadow: none !important;
        font-size: 11px !important;
        cursor: pointer !important;
    }

    .app-sidebar-logout:hover {
        color: var(--pi-red) !important;
        background: #fff1f1 !important;
    }

    .sidebar-overlay-bg {
        display: none;
    }

    body:has(.app-sidebar) .main-content {
        width: calc(100% - var(--pi-sidebar)) !important;
        max-width: calc(100% - var(--pi-sidebar)) !important;
        min-height: 100vh !important;
        margin: 0 0 0 var(--pi-sidebar) !important;
        padding: 0 !important;
        background: var(--pi-bg) !important;
    }

    .app-topbar {
        position: sticky !important;
        top: 0 !important;
        z-index: 100 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        min-height: 68px !important;
        padding: 0 28px !important;
        overflow: visible !important;
        color: var(--pi-text) !important;
        background: rgba(255, 255, 255, .96) !important;
        border: 0 !important;
        border-bottom: 1px solid var(--pi-line) !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        backdrop-filter: blur(10px) !important;
    }

    .app-topbar-title span,
    .app-topbar-title strong {
        display: block !important;
    }

    .app-topbar-context {
        margin-bottom: 2px !important;
        color: var(--pi-quiet) !important;
        font-size: 9px !important;
        font-weight: 600 !important;
        letter-spacing: .05em !important;
        text-transform: uppercase !important;
    }

    .app-topbar-title strong {
        font-size: 13px !important;
        font-weight: 650 !important;
    }

    .app-topbar-date {
        display: inline-flex !important;
        align-items: center !important;
        gap: 7px !important;
        color: var(--pi-muted) !important;
        font-size: 10px !important;
        font-weight: 500 !important;
    }

    .app-topbar-date i {
        color: var(--pi-quiet) !important;
        font-size: 11px !important;
    }

    .app-user {
        display: flex !important;
        align-items: center !important;
        gap: 9px !important;
    }

    .app-user-avatar {
        display: grid !important;
        place-items: center !important;
        width: 32px !important;
        height: 32px !important;
        color: var(--pi-blue) !important;
        background: var(--pi-blue-soft) !important;
        border-radius: 50% !important;
        font-size: 11px !important;
        font-weight: 700 !important;
    }

    .app-user-copy strong,
    .app-user-copy small {
        display: block !important;
    }

    .app-user-copy strong {
        max-width: 160px !important;
        overflow: hidden !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }

    .app-user-copy small {
        margin-top: 1px !important;
        color: var(--pi-muted) !important;
        font-size: 9px !important;
    }

    .app-icon-button,
    .app-mobile-trigger {
        display: grid !important;
        place-items: center !important;
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        color: var(--pi-muted) !important;
        background: var(--pi-surface) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: 7px !important;
        box-shadow: none !important;
    }

    .app-mobile-trigger {
        display: none !important;
    }

    /* Content rhythm */
    body:has(.app-sidebar) .content-area,
    body:has(.app-sidebar) .dashboard-content {
        width: 100% !important;
        max-width: 1500px !important;
        margin: 0 auto !important;
        padding: 26px 28px 42px !important;
        background: transparent !important;
    }

    .content-container {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
    }

    .dashboard-header,
    .page-header {
        margin: 0 0 18px !important;
        padding: 0 !important;
        background: transparent !important;
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .page-title,
    .dashboard-title,
    .section-title {
        color: var(--pi-text) !important;
        font-family: "Inter", system-ui, sans-serif !important;
        letter-spacing: -.025em !important;
    }

    .page-title,
    .dashboard-title,
    main h1 {
        margin: 0 0 6px !important;
        font-size: clamp(22px, 2.4vw, 28px) !important;
        font-weight: 700 !important;
        line-height: 1.25 !important;
    }

    .dashboard-title i,
    .page-title i,
    .section-title i,
    .filter-title i,
    .chart-title i {
        display: none !important;
    }

    .dashboard-subtitle,
    .page-subtitle,
    .welcome-description,
    .summary-card-description,
    .workflow-subtitle,
    .school-cell-subtitle {
        color: var(--pi-muted) !important;
        font-size: 12px !important;
        line-height: 1.5 !important;
    }

    /* Surfaces */
    .card,
    .dashboard-card,
    .stat-card,
    .summary-card,
    .chart-card,
    .filter-section,
    .admin-workflow,
    .schools-table-card,
    .table-container,
    .students-table-container,
    .form-container,
    .content-card,
    .detail-card,
    .profile-card,
    .info-card,
    .report-card {
        color: var(--pi-text) !important;
        background: var(--pi-surface) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: var(--pi-radius) !important;
        box-shadow: none !important;
    }

    .card:hover,
    .stat-card:hover,
    .summary-card:hover {
        transform: none !important;
        box-shadow: none !important;
    }

    .stat-card::before,
    .summary-card::before,
    .card::before,
    .header-decoration,
    .header-glass {
        display: none !important;
    }

    .summary-cards,
    .stats-grid {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 12px !important;
        margin: 20px 0 !important;
    }

    .summary-card,
    .stat-card {
        min-height: 130px !important;
        padding: 18px !important;
    }

    .summary-card-header,
    .stat-header {
        margin: 0 0 16px !important;
    }

    .summary-card-title,
    .stat-label {
        color: var(--pi-muted) !important;
        font-size: 11px !important;
        font-weight: 500 !important;
    }

    .summary-card-value,
    .stat-value {
        color: var(--pi-text) !important;
        font-size: clamp(20px, 2.2vw, 28px) !important;
        font-weight: 650 !important;
        letter-spacing: -.04em !important;
    }

    .summary-card-icon,
    .stat-icon {
        display: grid !important;
        place-items: center !important;
        width: 34px !important;
        height: 34px !important;
        color: var(--pi-blue) !important;
        background: var(--pi-blue-soft) !important;
        border-radius: 7px !important;
        font-size: 13px !important;
    }

    .charts-section {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 12px !important;
        margin: 12px 0 24px !important;
    }

    /* Dashboard keeps only information needed for daily decisions. */
    .dashboard-content .admin-workflow,
    .content-area .admin-workflow {
        display: none !important;
    }

    .dashboard-content .charts-section .chart-card:nth-child(n+2),
    .content-area .charts-section .chart-card:nth-child(n+2) {
        display: none !important;
    }

    .dashboard-content .charts-section,
    .content-area .charts-section {
        grid-template-columns: minmax(0, 1fr) !important;
    }

    .dashboard-card {
        margin-top: 14px !important;
        overflow: hidden !important;
    }

    .dashboard-card .card-content {
        padding: 18px !important;
    }

    .chart-card {
        padding: 18px !important;
    }

    .chart-title,
    .section-title,
    .filter-title,
    .workflow-title {
        margin: 0 0 14px !important;
        color: var(--pi-text) !important;
        font-size: 14px !important;
        font-weight: 650 !important;
    }

    /* Filters and forms */
    .filter-section,
    .admin-workflow {
        margin: 18px 0 !important;
        padding: 18px !important;
    }

    .filter-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)) !important;
        gap: 12px !important;
    }

    label,
    .form-label,
    .filter-label {
        display: block;
        margin: 0 0 6px !important;
        color: #344054 !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        letter-spacing: 0 !important;
        text-transform: none !important;
    }

    input:not([type="checkbox"]):not([type="radio"]),
    select,
    textarea,
    .form-control,
    .form-select,
    .filter-input {
        width: 100% !important;
        max-width: 100% !important;
        min-height: 40px !important;
        padding: 8px 11px !important;
        color: var(--pi-text) !important;
        background: var(--pi-surface) !important;
        border: 1px solid #d0d5dd !important;
        border-radius: 7px !important;
        box-shadow: none !important;
        font-family: inherit !important;
        font-size: 12px !important;
    }

    textarea {
        min-height: 90px !important;
    }

    input:focus,
    select:focus,
    textarea:focus,
    .form-control:focus,
    .form-select:focus {
        border-color: var(--pi-blue) !important;
        outline: 3px solid rgba(40, 120, 240, .12) !important;
        box-shadow: none !important;
    }

    /* Buttons */
    .btn,
    .btn-filter,
    .btn-reset,
    .btn-primary,
    .btn-secondary,
    .action-btn,
    button[type="submit"]:not(.app-icon-button) {
        min-height: 36px !important;
        padding: 7px 13px !important;
        border-radius: 7px !important;
        box-shadow: none !important;
        font-family: inherit !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        line-height: 1.2 !important;
        text-decoration: none !important;
        transition: background .15s ease, border-color .15s ease !important;
    }

    .btn-primary,
    .btn-filter,
    button[type="submit"]:not(.btn-danger):not(.app-icon-button) {
        color: #fff !important;
        background: var(--pi-blue) !important;
        border: 1px solid var(--pi-blue) !important;
    }

    .btn-primary:hover,
    .btn-filter:hover,
    button[type="submit"]:not(.btn-danger):not(.app-icon-button):hover {
        background: var(--pi-blue-hover) !important;
        border-color: var(--pi-blue-hover) !important;
        transform: none !important;
    }

    .btn-secondary,
    .btn-reset,
    .btn-light,
    .btn-outline-primary {
        color: #344054 !important;
        background: var(--pi-surface) !important;
        border: 1px solid #d0d5dd !important;
    }

    .btn-danger,
    .btn-outline-danger {
        color: var(--pi-red) !important;
        background: #fff5f5 !important;
        border-color: #fecaca !important;
    }

    /* Tables */
    .table-responsive,
    .table-container,
    .students-table-container,
    .schools-table-wrap {
        max-width: 100% !important;
        overflow-x: auto !important;
    }

    table,
    .table,
    .schools-table {
        width: 100% !important;
        margin: 0 !important;
        color: var(--pi-text) !important;
        background: var(--pi-surface) !important;
        border-collapse: collapse !important;
        font-size: 11px !important;
    }

    thead,
    .table thead {
        background: var(--pi-surface-soft) !important;
    }

    th,
    .table th {
        padding: 10px 12px !important;
        color: var(--pi-muted) !important;
        background: var(--pi-surface-soft) !important;
        border: 0 !important;
        border-bottom: 1px solid var(--pi-line) !important;
        font-size: 10px !important;
        font-weight: 600 !important;
        letter-spacing: .025em !important;
        text-transform: uppercase !important;
        white-space: nowrap !important;
    }

    td,
    .table td {
        padding: 11px 12px !important;
        vertical-align: middle !important;
        color: #344054 !important;
        background: var(--pi-surface) !important;
        border: 0 !important;
        border-bottom: 1px solid #eef0f3 !important;
    }

    tbody tr:hover td {
        background: #fbfcfe !important;
    }

    .badge,
    [class*="status-"] {
        border-radius: 999px !important;
        box-shadow: none !important;
        font-size: 9px !important;
        font-weight: 600 !important;
    }

    .empty-state {
        padding: 44px 20px !important;
        color: var(--pi-muted) !important;
        background: var(--pi-surface) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: var(--pi-radius) !important;
        text-align: center !important;
    }

    /* Remove noisy repeated copy on information-dense pages */
    .workflow-step-text,
    .summary-card-description,
    .school-cell-subtitle {
        display: none !important;
    }

    @media (max-width: 980px) {
        .summary-cards,
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }

        .charts-section {
            grid-template-columns: 1fr !important;
        }
    }

    @media (max-width: 760px) {
        .app-sidebar {
            width: min(82vw, 280px) !important;
            transform: translateX(-102%) !important;
            transition: transform .18s ease !important;
        }

        .app-sidebar.is-open {
            transform: translateX(0) !important;
        }

        .sidebar-overlay-bg {
            position: fixed !important;
            inset: 0 !important;
            z-index: 990 !important;
            display: block !important;
            visibility: hidden !important;
            background: rgba(16, 24, 40, .32) !important;
            opacity: 0 !important;
            transition: .18s ease !important;
        }

        .sidebar-overlay-bg.is-active {
            visibility: visible !important;
            opacity: 1 !important;
        }

        body:has(.app-sidebar) .main-content {
            width: 100% !important;
            max-width: 100% !important;
            margin-left: 0 !important;
            padding: 0 !important;
            overflow-x: hidden !important;
        }

        .app-topbar {
            min-height: 62px !important;
            padding: 0 16px !important;
        }

        .app-mobile-trigger {
            display: grid !important;
            margin-right: 10px !important;
        }

        .app-topbar-title {
            margin-right: auto !important;
        }

        .app-topbar-date {
            display: none !important;
        }

        .app-user-copy {
            display: none !important;
        }

        body:has(.app-sidebar) .content-area,
        body:has(.app-sidebar) .dashboard-content {
            padding: 20px 15px 32px !important;
        }

        .summary-cards,
        .stats-grid {
            grid-template-columns: 1fr !important;
        }

        table {
            min-width: 680px !important;
        }
    }

    @media print {
        html,
        body,
        main {
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        .app-sidebar,
        .app-topbar,
        .sidebar-overlay-bg {
            display: none !important;
        }

        body:has(.app-sidebar) .main-content {
            width: 100% !important;
            max-width: 100% !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        body:has(.app-sidebar) .content-area,
        body:has(.app-sidebar) .dashboard-content,
        body:has(.app-sidebar) .receipt-page,
        body:has(.app-sidebar) .receipt-paper {
            width: 100% !important;
            max-width: none !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
    }

    /*
     * Legacy page normalization
     * These selectors intentionally outrank the page-level styles that came
     * from the hosted version of the application.
     */
    body:has(.app-sidebar) .main-content .content-area .page-header,
    body:has(.app-sidebar) .main-content .dashboard-content .page-header,
    body:has(.app-sidebar) .main-content .content-area .dashboard-header {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 14px !important;
        margin: 0 0 18px !important;
        padding: 0 !important;
        color: var(--pi-text) !important;
        background: transparent !important;
        border: 0 !important;
        box-shadow: none !important;
    }

    body:has(.app-sidebar) .main-content .content-area .page-title,
    body:has(.app-sidebar) .main-content .dashboard-content .page-title,
    body:has(.app-sidebar) .main-content .content-area .dashboard-title,
    body:has(.app-sidebar) .main-content .dashboard-content .dashboard-title {
        margin: 0 !important;
        color: var(--pi-text) !important;
        background: none !important;
        background-clip: border-box !important;
        font-size: clamp(22px, 2.3vw, 28px) !important;
        font-weight: 700 !important;
        line-height: 1.25 !important;
        -webkit-background-clip: border-box !important;
        -webkit-text-fill-color: currentColor !important;
        text-shadow: none !important;
    }

    body:has(.app-sidebar) .main-content .content-area .page-title::after,
    body:has(.app-sidebar) .main-content .content-area .page-title::before,
    body:has(.app-sidebar) .main-content .content-area .section-title::after,
    body:has(.app-sidebar) .main-content .content-area .section-title::before {
        display: none !important;
    }

    /* Filters */
    body:has(.app-sidebar) .main-content .filter-card,
    body:has(.app-sidebar) .main-content .filter-container,
    body:has(.app-sidebar) .main-content form.filter-form {
        display: block !important;
        margin: 0 0 18px !important;
        padding: 16px !important;
        color: var(--pi-text) !important;
        background: var(--pi-surface) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: var(--pi-radius) !important;
        box-shadow: none !important;
    }

    body:has(.app-sidebar) .main-content .filter-container > h3,
    body:has(.app-sidebar) .main-content .filter-card > h3 {
        margin: 0 0 14px !important;
        color: var(--pi-text) !important;
        font-size: 13px !important;
        font-weight: 650 !important;
    }

    body:has(.app-sidebar) .main-content .filter-container > h3 i,
    body:has(.app-sidebar) .main-content .filter-card > h3 i,
    body:has(.app-sidebar) .main-content .filter-label i,
    body:has(.app-sidebar) .main-content label i {
        display: none !important;
    }

    body:has(.app-sidebar) .main-content .filter-row,
    body:has(.app-sidebar) .main-content .filter-form {
        gap: 12px !important;
    }

    body:has(.app-sidebar) .main-content .filter-row {
        display: grid !important;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)) !important;
        align-items: end !important;
        margin: 0 0 12px !important;
    }

    body:has(.app-sidebar) .main-content .filter-row:last-child {
        margin-bottom: 0 !important;
    }

    body:has(.app-sidebar) .main-content .filter-card > form.filter-form {
        margin: 0 !important;
        padding: 0 !important;
        background: transparent !important;
        border: 0 !important;
        border-radius: 0 !important;
    }

    body:has(.app-sidebar) .main-content .content-area > form.filter-form {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(150px, 1fr)) auto auto !important;
        align-items: end !important;
    }

    body:has(.app-sidebar) .main-content .filter-actions,
    body:has(.app-sidebar) .main-content .filter-buttons,
    body:has(.app-sidebar) .main-content .header-actions {
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        gap: 8px !important;
    }

    /* Consistent actions, including custom buttons from old pages */
    body:has(.app-sidebar) .main-content :is(
        .btn-primary,
        .filter-btn,
        .btn-apply,
        .btn-generate,
        .btn-process
    ) {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        min-height: 36px !important;
        padding: 7px 12px !important;
        color: #fff !important;
        background: var(--pi-blue) !important;
        border: 1px solid var(--pi-blue) !important;
        border-radius: 7px !important;
        box-shadow: none !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        transform: none !important;
    }

    body:has(.app-sidebar) .main-content :is(
        .reset-btn,
        .btn-reset,
        .btn-detail,
        .btn-edit,
        .details-toggle,
        .print-btn
    ) {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 5px !important;
        min-height: 32px !important;
        padding: 6px 9px !important;
        color: #344054 !important;
        background: var(--pi-surface) !important;
        border: 1px solid #d0d5dd !important;
        border-radius: 6px !important;
        box-shadow: none !important;
        font-size: 10px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        transform: none !important;
    }

    body:has(.app-sidebar) .main-content .btn-delete,
    body:has(.app-sidebar) .main-content .btn-danger {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 5px !important;
        min-height: 32px !important;
        padding: 6px 9px !important;
        color: #b42318 !important;
        background: #fff5f5 !important;
        border: 1px solid #fecaca !important;
        border-radius: 6px !important;
        box-shadow: none !important;
        font-size: 10px !important;
        font-weight: 600 !important;
        transform: none !important;
    }

    body:has(.app-sidebar) .main-content .btn-warning {
        color: #344054 !important;
        background: var(--pi-surface) !important;
        border-color: #d0d5dd !important;
    }

    body:has(.app-sidebar) .main-content :is(
        .btn-primary,
        .filter-btn,
        .btn-apply,
        .btn-generate,
        .btn-process,
        .reset-btn,
        .btn-reset,
        .btn-detail,
        .btn-edit,
        .details-toggle,
        .btn-delete,
        .print-btn
    )::before,
    body:has(.app-sidebar) .main-content :is(
        .btn-primary,
        .filter-btn,
        .btn-apply,
        .btn-generate,
        .btn-process,
        .reset-btn,
        .btn-reset,
        .btn-detail,
        .btn-edit,
        .details-toggle,
        .btn-delete,
        .print-btn
    )::after {
        display: none !important;
    }

    /* Data and metric surfaces */
    body:has(.app-sidebar) .main-content .table-card,
    body:has(.app-sidebar) .main-content .subtotal-section {
        margin: 0 0 18px !important;
        overflow: hidden !important;
        color: var(--pi-text) !important;
        background: var(--pi-surface) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: var(--pi-radius) !important;
        box-shadow: none !important;
    }

    body:has(.app-sidebar) .main-content .table-header {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        min-height: 50px !important;
        padding: 0 16px !important;
        color: var(--pi-text) !important;
        background: var(--pi-surface) !important;
        border: 0 !important;
        border-bottom: 1px solid var(--pi-line) !important;
        box-shadow: none !important;
    }

    body:has(.app-sidebar) .main-content .table-header h3,
    body:has(.app-sidebar) .main-content .table-title {
        margin: 0 !important;
        color: var(--pi-text) !important;
        font-size: 14px !important;
        font-weight: 650 !important;
    }

    body:has(.app-sidebar) .main-content .table-header h3 i,
    body:has(.app-sidebar) .main-content .table-title i {
        display: none !important;
    }

    body:has(.app-sidebar) .main-content .stats-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        gap: 10px !important;
        margin: 0 0 18px !important;
    }

    body:has(.app-sidebar) .main-content .stats-grid .stat-card {
        min-height: 96px !important;
        padding: 15px !important;
        background: var(--pi-surface) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: var(--pi-radius) !important;
        box-shadow: none !important;
    }

    body:has(.app-sidebar) .main-content .stats-grid .stat-number {
        color: var(--pi-text) !important;
        font-size: 24px !important;
        font-weight: 650 !important;
    }

    body:has(.app-sidebar) .main-content .stats-grid .stat-icon {
        color: var(--pi-blue) !important;
        background: var(--pi-blue-soft) !important;
    }

    body:has(.app-sidebar) .main-content .student-count .count-number {
        color: var(--pi-text) !important;
        font-size: 16px !important;
        font-weight: 650 !important;
    }

    /* Grouped billing */
    body:has(.app-sidebar) .main-content .school-section,
    body:has(.app-sidebar) .main-content .class-item {
        margin: 0 0 10px !important;
        overflow: hidden !important;
        background: var(--pi-surface) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: var(--pi-radius) !important;
        box-shadow: none !important;
        transform: none !important;
    }

    body:has(.app-sidebar) .main-content .school-header,
    body:has(.app-sidebar) .main-content .class-header {
        min-height: 58px !important;
        padding: 14px 16px !important;
        color: var(--pi-text) !important;
        background: var(--pi-surface) !important;
        border: 0 !important;
        box-shadow: none !important;
    }

    body:has(.app-sidebar) .main-content .school-header h3,
    body:has(.app-sidebar) .main-content .class-header h4 {
        margin: 0 !important;
        color: var(--pi-text) !important;
        font-size: 14px !important;
        font-weight: 650 !important;
    }

    body:has(.app-sidebar) .main-content .school-header i,
    body:has(.app-sidebar) .main-content .class-header i {
        color: var(--pi-muted) !important;
    }

    body:has(.app-sidebar) .main-content .school-content,
    body:has(.app-sidebar) .main-content .students-table-container {
        background: var(--pi-surface) !important;
        border: 0 !important;
        border-top: 1px solid var(--pi-line) !important;
        box-shadow: none !important;
    }

    /* Transaction history cards become compact neutral rows */
    body:has(.app-sidebar) .main-content .transactions-list {
        display: grid !important;
        gap: 8px !important;
    }

    body:has(.app-sidebar) .main-content .transaction-card {
        margin: 0 !important;
        overflow: hidden !important;
        background: var(--pi-surface) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: var(--pi-radius) !important;
        box-shadow: none !important;
        transform: none !important;
    }

    body:has(.app-sidebar) .main-content .transaction-card .card-header {
        min-height: 0 !important;
        padding: 13px 15px !important;
        color: var(--pi-text) !important;
        background: var(--pi-surface) !important;
        border: 0 !important;
        box-shadow: none !important;
    }

    body:has(.app-sidebar) .main-content .transaction-card .student-name {
        color: var(--pi-text) !important;
        font-size: 13px !important;
        font-weight: 650 !important;
    }

    body:has(.app-sidebar) .main-content .transaction-card .source-badge {
        width: fit-content !important;
        margin: 5px 0 !important;
        padding: 3px 7px !important;
        color: var(--pi-blue) !important;
        background: var(--pi-blue-soft) !important;
        border: 0 !important;
        border-radius: 999px !important;
        font-size: 9px !important;
    }

    body:has(.app-sidebar) .main-content .transaction-card .transaction-date,
    body:has(.app-sidebar) .main-content .transaction-card .detail-item {
        color: var(--pi-muted) !important;
        font-size: 10px !important;
    }

    body:has(.app-sidebar) .main-content .transaction-card .detail-item i,
    body:has(.app-sidebar) .main-content .transaction-card .transaction-date i {
        color: var(--pi-quiet) !important;
    }

    body:has(.app-sidebar) .main-content .transaction-card .total-amount {
        padding: 0 !important;
        color: var(--pi-text) !important;
        background: transparent !important;
        border: 0 !important;
        border-radius: 0 !important;
        font-size: 16px !important;
        font-weight: 650 !important;
        box-shadow: none !important;
    }

    body:has(.app-sidebar) .main-content .transaction-card .card-body {
        padding: 14px !important;
        background: var(--pi-surface-soft) !important;
        border-top: 1px solid var(--pi-line) !important;
    }

    /* Report subtotal */
    body:has(.app-sidebar) .main-content .subtotal-section {
        padding: 16px !important;
    }

    body:has(.app-sidebar) .main-content .subtotal-title {
        margin: 0 0 12px !important;
        color: var(--pi-text) !important;
        font-size: 13px !important;
        font-weight: 650 !important;
    }

    body:has(.app-sidebar) .main-content .subtotal-grid {
        display: grid !important;
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        gap: 10px !important;
    }

    body:has(.app-sidebar) .main-content .subtotal-item {
        min-height: 78px !important;
        padding: 13px !important;
        color: var(--pi-text) !important;
        background: var(--pi-surface-soft) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: 8px !important;
        box-shadow: none !important;
    }

    body:has(.app-sidebar) .main-content .subtotal-label {
        color: var(--pi-muted) !important;
        font-size: 10px !important;
    }

    body:has(.app-sidebar) .main-content .subtotal-value {
        margin-top: 6px !important;
        color: var(--pi-text) !important;
        font-size: 17px !important;
        font-weight: 650 !important;
    }

    /* Empty states and badges */
    body:has(.app-sidebar) .main-content .empty-state i {
        color: var(--pi-quiet) !important;
        background: transparent !important;
    }

    body:has(.app-sidebar) .main-content .empty-state h3,
    body:has(.app-sidebar) .main-content .empty-state h4 {
        color: var(--pi-text) !important;
        font-size: 14px !important;
    }

    body:has(.app-sidebar) .main-content .empty-state p {
        max-width: 520px !important;
        margin: 6px auto 15px !important;
        color: var(--pi-muted) !important;
        font-size: 11px !important;
    }

    body:has(.app-sidebar) .main-content :is(.badge-success, .bg-success, .status-success) {
        color: #067647 !important;
        background: #ecfdf3 !important;
        border-color: #abefc6 !important;
    }

    body:has(.app-sidebar) .main-content :is(.badge-info, .bg-info) {
        color: #175cd3 !important;
        background: var(--pi-blue-soft) !important;
        border-color: #b2ccff !important;
    }

    /* Modals */
    body:has(.app-sidebar) .modal-content {
        color: var(--pi-text) !important;
        background: var(--pi-surface) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: 10px !important;
        box-shadow: 0 18px 48px rgba(16, 24, 40, .14) !important;
    }

    body:has(.app-sidebar) .modal-header,
    body:has(.app-sidebar) .modal-footer {
        padding: 14px 16px !important;
        background: var(--pi-surface) !important;
        border-color: var(--pi-line) !important;
    }

    body:has(.app-sidebar) .modal-body {
        padding: 16px !important;
    }

    body:has(.app-sidebar) .modal-title {
        color: var(--pi-text) !important;
        font-size: 14px !important;
        font-weight: 650 !important;
    }

    /* Forms: one compact visual language across old and new screens */
    body:has(.app-sidebar) .main-content .page-header :is(h1, h2),
    body:has(.app-sidebar) .main-content .page-title :is(h1, h2),
    body:has(.app-sidebar) .main-content > :is(h1, h2),
    body:has(.app-sidebar) .content-area > :is(h1, h2) {
        margin: 0 !important;
        color: var(--pi-text) !important;
        background: none !important;
        background-clip: border-box !important;
        -webkit-background-clip: border-box !important;
        -webkit-text-fill-color: currentColor !important;
        font-size: 24px !important;
        font-weight: 700 !important;
        line-height: 1.25 !important;
    }

    body:has(.app-sidebar) .main-content .page-header :is(h1, h2) > i,
    body:has(.app-sidebar) .main-content .page-title :is(h1, h2) > i {
        display: none !important;
    }

    body:has(.app-sidebar) .main-content .cards-container {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 12px !important;
        margin: 0 0 12px !important;
    }

    body:has(.app-sidebar) .main-content :is(.form-card, .import-card) {
        margin: 0 0 12px !important;
        padding: 18px !important;
        overflow: visible !important;
        color: var(--pi-text) !important;
        background: var(--pi-surface) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: var(--pi-radius) !important;
        box-shadow: none !important;
        backdrop-filter: none !important;
        transform: none !important;
    }

    body:has(.app-sidebar) .main-content :is(.form-card, .import-card):hover {
        box-shadow: none !important;
        transform: none !important;
    }

    body:has(.app-sidebar) .main-content .form-card:has(> .form-header) {
        padding: 0 !important;
        overflow: hidden !important;
    }

    body:has(.app-sidebar) .main-content .form-header {
        margin: 0 !important;
        padding: 15px 18px !important;
        color: var(--pi-text) !important;
        background: var(--pi-surface) !important;
        border: 0 !important;
        border-bottom: 1px solid var(--pi-line) !important;
        box-shadow: none !important;
    }

    body:has(.app-sidebar) .main-content .form-header h3,
    body:has(.app-sidebar) .main-content .form-card > h3,
    body:has(.app-sidebar) .main-content .section-header h4 {
        margin: 0 !important;
        padding: 0 !important;
        color: var(--pi-text) !important;
        background: none !important;
        border: 0 !important;
        font-size: 13px !important;
        font-weight: 650 !important;
        line-height: 1.4 !important;
    }

    body:has(.app-sidebar) .main-content :is(.form-header h3, .form-card > h3, .section-header h4) > i {
        display: none !important;
    }

    body:has(.app-sidebar) .main-content .form-header p {
        display: none !important;
    }

    body:has(.app-sidebar) .main-content .form-card > h3 {
        margin-bottom: 16px !important;
        padding-bottom: 11px !important;
        border-bottom: 1px solid var(--pi-line) !important;
    }

    body:has(.app-sidebar) .main-content .form-section {
        margin: 0 !important;
        padding: 18px !important;
        background: var(--pi-surface) !important;
        border: 0 !important;
        border-bottom: 1px solid var(--pi-line) !important;
    }

    body:has(.app-sidebar) .main-content .form-section:last-of-type {
        border-bottom: 0 !important;
    }

    body:has(.app-sidebar) .main-content .section-header {
        margin: 0 0 14px !important;
        padding: 0 !important;
    }

    body:has(.app-sidebar) .main-content :is(.form-grid, .form-row) {
        gap: 12px !important;
    }

    body:has(.app-sidebar) .main-content .form-row {
        margin: 0 !important;
    }

    body:has(.app-sidebar) .main-content .form-group {
        min-width: 0 !important;
        margin: 0 0 14px !important;
        padding: 0 !important;
    }

    body:has(.app-sidebar) .main-content .form-group:last-child {
        margin-bottom: 0 !important;
    }

    body:has(.app-sidebar) .main-content :is(.form-grid, .form-row) > .form-group {
        margin-bottom: 0 !important;
    }

    body:has(.app-sidebar) .main-content :is(.form-label, label) > i {
        display: none !important;
    }

    body:has(.app-sidebar) .main-content :is(.form-text, .text-muted) {
        margin-top: 5px !important;
        color: var(--pi-muted) !important;
        font-size: 10px !important;
        line-height: 1.45 !important;
    }

    body:has(.app-sidebar) .main-content .input-group {
        align-items: center !important;
        gap: 8px !important;
    }

    body:has(.app-sidebar) .main-content :is(.form-actions, .action-buttons) {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 8px !important;
        margin: 0 !important;
        padding: 15px 18px !important;
        background: var(--pi-surface) !important;
        border: 0 !important;
        border-top: 1px solid var(--pi-line) !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        text-align: left !important;
    }

    body:has(.app-sidebar) .main-content form > .action-buttons {
        border: 1px solid var(--pi-line) !important;
        border-radius: var(--pi-radius) !important;
    }

    body:has(.app-sidebar) .main-content :is(.form-actions, .action-buttons) .btn {
        margin: 0 !important;
    }

    body:has(.app-sidebar) .main-content :is(.btn-default, .btn-success) {
        min-height: 36px !important;
        margin: 0 !important;
        padding: 7px 12px !important;
        color: #344054 !important;
        background: var(--pi-surface) !important;
        border: 1px solid #d0d5dd !important;
        border-radius: 7px !important;
        box-shadow: none !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        transform: none !important;
    }

    body:has(.app-sidebar) .main-content .btn-default.active {
        color: var(--pi-blue) !important;
        background: var(--pi-blue-soft) !important;
        border-color: #b2ccff !important;
    }

    body:has(.app-sidebar) .main-content :is(.target-type-container, .spp-container, .radio-group) {
        padding: 12px !important;
        background: var(--pi-surface-soft) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: 8px !important;
        box-shadow: none !important;
    }

    body:has(.app-sidebar) .main-content .radio-group {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 18px !important;
        margin: 0 !important;
        padding: 0 !important;
        background: transparent !important;
        border: 0 !important;
    }

    body:has(.app-sidebar) .main-content input[type="file"] {
        width: 100% !important;
        max-width: 560px !important;
        padding: 7px !important;
    }

    /* Import keeps the primary task visible; technical references are optional. */
    body:has(.app-sidebar) .main-content .import-card {
        max-width: 640px !important;
    }

    body:has(.app-sidebar) .main-content .import-guide,
    body:has(.app-sidebar) .main-content .import-reference {
        max-width: 760px !important;
        margin: 12px 0 0 !important;
        padding: 16px 18px !important;
        color: var(--pi-text) !important;
        background: var(--pi-surface) !important;
        border: 1px solid var(--pi-line) !important;
        border-radius: var(--pi-radius) !important;
        box-shadow: none !important;
    }

    body:has(.app-sidebar) .main-content .import-guide h3 {
        margin: 0 0 9px !important;
        font-size: 13px !important;
        font-weight: 650 !important;
    }

    body:has(.app-sidebar) .main-content .import-guide ol {
        margin: 0 !important;
        padding-left: 18px !important;
        color: var(--pi-muted) !important;
        font-size: 11px !important;
    }

    body:has(.app-sidebar) .main-content .import-guide li + li {
        margin-top: 4px !important;
    }

    body:has(.app-sidebar) .main-content .import-reference summary {
        color: #344054 !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
    }

    body:has(.app-sidebar) .main-content .import-reference-content {
        margin-top: 14px !important;
        padding-top: 14px !important;
        border-top: 1px solid var(--pi-line) !important;
    }

    body:has(.app-sidebar) .main-content .import-reference .info-title {
        margin: 0 0 8px !important;
        color: var(--pi-text) !important;
        font-size: 12px !important;
    }

    body:has(.app-sidebar) .main-content .import-reference .info-list li {
        display: grid !important;
        grid-template-columns: 130px 1fr !important;
        gap: 12px !important;
        padding: 9px 0 !important;
        color: var(--pi-muted) !important;
        border-color: var(--pi-line) !important;
        font-size: 10px !important;
    }

    body:has(.app-sidebar) .main-content .import-reference .info-list .label {
        min-width: 0 !important;
        color: #344054 !important;
        font-weight: 600 !important;
    }

    body:has(.app-sidebar) .main-content .import-example {
        display: none !important;
    }

    /* Academic workflows and report group headings */
    body:has(.app-sidebar) .main-content .form-container > h2 {
        margin: 0 0 20px !important;
        color: var(--pi-text) !important;
        background: none !important;
        background-clip: border-box !important;
        -webkit-background-clip: border-box !important;
        -webkit-text-fill-color: currentColor !important;
        font-size: 24px !important;
        font-weight: 700 !important;
    }

    body:has(.app-sidebar) .main-content .form-container > h2 > i {
        display: none !important;
    }

    body:has(.app-sidebar) .content-area:has(> .page-header) > .form-container > h2 {
        margin-bottom: 16px !important;
        font-size: 14px !important;
        font-weight: 650 !important;
    }

    body:has(.app-sidebar) .main-content .school-name-header {
        margin: 12px 0 0 !important;
        padding: 11px 13px !important;
        color: var(--pi-text) !important;
        background: var(--pi-surface-soft) !important;
        border: 1px solid var(--pi-line) !important;
        border-bottom: 0 !important;
        border-radius: 8px 8px 0 0 !important;
        box-shadow: none !important;
        font-size: 11px !important;
        font-weight: 650 !important;
    }

    body:has(.app-sidebar) .main-content form.graduation-filter {
        display: grid !important;
        grid-template-columns: repeat(4, minmax(140px, 1fr)) auto !important;
        align-items: end !important;
        gap: 12px !important;
    }

    body:has(.app-sidebar) .main-content form.graduation-filter .form-group {
        margin: 0 !important;
    }

    body:has(.app-sidebar) .main-content .empty-state > i {
        color: var(--pi-quiet) !important;
    }

    @media (max-width: 980px) {
        body:has(.app-sidebar) .main-content .stats-grid,
        body:has(.app-sidebar) .main-content .subtotal-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }

        body:has(.app-sidebar) .main-content .content-area > form.filter-form {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }

        body:has(.app-sidebar) .main-content .cards-container {
            grid-template-columns: 1fr !important;
        }

        body:has(.app-sidebar) .main-content form.graduation-filter {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }

    @media (max-width: 600px) {
        body:has(.app-sidebar) .main-content .stats-grid,
        body:has(.app-sidebar) .main-content .subtotal-grid {
            grid-template-columns: 1fr !important;
        }

        body:has(.app-sidebar) .main-content .page-header {
            align-items: flex-start !important;
            flex-direction: column !important;
        }

        body:has(.app-sidebar) .main-content .content-area > form.filter-form {
            grid-template-columns: 1fr !important;
        }

        body:has(.app-sidebar) .main-content .filter-row {
            grid-template-columns: 1fr !important;
        }

        body:has(.app-sidebar) .main-content .filter-actions {
            align-items: stretch !important;
            width: 100% !important;
        }

        body:has(.app-sidebar) .main-content .input-group {
            align-items: stretch !important;
            flex-direction: column !important;
        }

        body:has(.app-sidebar) .main-content .input-group > :is(.btn, button) {
            width: 100% !important;
        }

        body:has(.app-sidebar) .main-content :is(.form-grid.two-columns, .form-grid.three-columns) {
            grid-template-columns: 1fr !important;
        }

        body:has(.app-sidebar) .main-content .form-row {
            flex-direction: column !important;
        }

        body:has(.app-sidebar) .main-content .form-row > [class*="col-md-"] {
            flex: 1 1 auto !important;
            width: 100% !important;
            max-width: none !important;
        }

        body:has(.app-sidebar) .main-content form.graduation-filter {
            grid-template-columns: 1fr !important;
        }

        body:has(.app-sidebar) .main-content :is(.form-actions, .action-buttons) {
            align-items: stretch !important;
            flex-direction: column !important;
        }
    }
</style>
