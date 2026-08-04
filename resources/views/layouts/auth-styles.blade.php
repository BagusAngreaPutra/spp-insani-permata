<style>
    :root {
        --green-950: #0b2f22;
        --green-900: #103e2d;
        --green-800: #15533b;
        --green-700: #1d6b4c;
        --green-600: #25845d;
        --green-100: #dff3e8;
        --green-50: #f1f9f5;
        --lime: #d9f475;
        --text: #101828;
        --muted: #667085;
        --quiet: #98a2b3;
        --line: #e4e7ec;
        --soft: #f8faf9;
        --white: #fff;
    }

    * { box-sizing: border-box; }
    html, body { min-height: 100%; }
    body {
        min-height: 100vh;
        margin: 0;
        color: var(--text);
        background: var(--soft);
        font-family: "Inter", system-ui, sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    button, input { font: inherit; }
    svg { display: block; }

    .auth-shell { min-height: 100vh; display: grid; grid-template-columns: minmax(440px, .9fr) minmax(520px, 1.1fr); }
    .auth-showcase {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 42px clamp(42px, 5vw, 76px);
        color: #fff;
        background: var(--green-950);
    }
    .auth-showcase::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -2;
        opacity: .16;
        background-image: radial-gradient(#c7e4d5 1px, transparent 1px);
        background-size: 23px 23px;
    }
    .auth-showcase::after {
        content: "";
        position: absolute;
        z-index: -1;
        width: 470px;
        height: 470px;
        right: -245px;
        top: -225px;
        background: rgba(69, 175, 123, .25);
        border: 65px solid rgba(255, 255, 255, .035);
        border-radius: 50%;
    }
    .showcase-brand { width: max-content; display: flex; align-items: center; gap: 12px; color: #fff; text-decoration: none; }
    .showcase-brand img { width: 45px; height: 45px; object-fit: cover; border: 3px solid rgba(255, 255, 255, .15); border-radius: 50%; }
    .showcase-brand strong, .showcase-brand span { display: block; }
    .showcase-brand strong { font-size: 13px; }
    .showcase-brand span { margin-top: 3px; color: #a8c8b8; font-size: 8px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; }
    .showcase-content { max-width: 520px; margin: 72px 0; }
    .showcase-kicker { display: flex; align-items: center; gap: 9px; margin-bottom: 21px; color: #91d2b0; font-size: 9px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .showcase-kicker::before { content: ""; width: 25px; height: 1px; background: #71c69a; }
    .showcase-content h2 { margin: 0; font-size: clamp(36px, 4.3vw, 58px); line-height: 1.06; letter-spacing: -.055em; }
    .showcase-content h2 em { position: relative; color: #a7dfc1; font-style: normal; }
    .showcase-content h2 em::after { content: ""; position: absolute; height: 6px; right: 0; bottom: 0; left: 0; z-index: -1; background: rgba(217, 244, 117, .8); border-radius: 99px; transform: rotate(-1deg); }
    .showcase-content > p { max-width: 450px; margin: 22px 0 30px; color: #b3cbbf; font-size: 12px; line-height: 1.8; }
    .showcase-points { display: grid; gap: 10px; }
    .showcase-point { display: flex; align-items: center; gap: 10px; color: #d9e8e1; font-size: 10px; font-weight: 600; }
    .showcase-point i { width: 25px; height: 25px; display: grid; place-items: center; color: #91d2b0; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.09); border-radius: 6px; }
    .showcase-point svg { width: 12px; height: 12px; }
    .showcase-footer { display: flex; justify-content: space-between; gap: 20px; color: #789f8c; font-size: 8px; }

    .auth-area { position: relative; display: grid; place-items: center; padding: 48px; background: #fbfcfc; }
    .auth-area::before {
        content: "";
        position: absolute;
        width: 320px;
        height: 320px;
        right: 0;
        bottom: 0;
        opacity: .27;
        background-image: radial-gradient(#91b7a5 1px, transparent 1px);
        background-size: 22px 22px;
        mask-image: linear-gradient(135deg, transparent, #000);
    }
    .auth-panel { position: relative; z-index: 1; width: min(100%, 430px); }
    .mobile-brand { display: none; }
    .form-badge { width: max-content; display: inline-flex; align-items: center; gap: 7px; margin-bottom: 20px; padding: 6px 9px; color: var(--green-700); background: var(--green-50); border: 1px solid #d5ebdf; border-radius: 5px; font-size: 8px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
    .form-badge i { width: 6px; height: 6px; background: var(--green-500); border-radius: 50%; }
    .auth-panel h1 { margin: 0; color: var(--green-950); font-size: 34px; letter-spacing: -.045em; }
    .auth-subtitle { margin: 9px 0 30px; color: var(--muted); font-size: 11px; line-height: 1.7; }
    .auth-error { display: flex; gap: 9px; margin-bottom: 19px; padding: 11px 12px; color: #b42318; background: #fff5f5; border: 1px solid #fecaca; border-radius: 7px; font-size: 9px; line-height: 1.5; }
    .auth-error svg { flex: 0 0 auto; width: 14px; height: 14px; }
    .field { margin-bottom: 17px; }
    .field label { display: block; margin-bottom: 7px; color: #344054; font-size: 9px; font-weight: 700; }
    .input-wrap { position: relative; }
    .input-icon { position: absolute; top: 50%; left: 13px; width: 16px; height: 16px; color: var(--quiet); transform: translateY(-50%); pointer-events: none; }
    .field input[type="text"],
    .field input[type="password"] {
        width: 100%;
        height: 47px;
        padding: 0 43px 0 41px;
        color: var(--text);
        background: #fff;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        font-size: 11px;
        outline: none;
        box-shadow: 0 1px 2px rgba(16, 24, 40, .025);
        transition: border-color .18s, box-shadow .18s;
    }
    .field input::placeholder { color: #b1b8b5; }
    .field input:focus { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(37, 132, 93, .11); }
    .password-toggle { position: absolute; top: 50%; right: 9px; width: 31px; height: 31px; display: grid; place-items: center; padding: 0; color: var(--quiet); background: transparent; border: 0; border-radius: 5px; cursor: pointer; transform: translateY(-50%); }
    .password-toggle:hover { color: var(--green-700); background: var(--green-50); }
    .password-toggle svg { width: 15px; height: 15px; }
    .form-options { display: flex; align-items: center; justify-content: space-between; gap: 15px; margin: 2px 0 21px; }
    .remember { display: flex; align-items: center; gap: 8px; color: var(--muted); font-size: 9px; }
    .remember input { width: 14px; height: 14px; margin: 0; accent-color: var(--green-700); }
    .remember label { cursor: pointer; }
    .secure-copy { display: flex; align-items: center; gap: 5px; color: var(--quiet); font-size: 8px; }
    .secure-copy svg { width: 12px; height: 12px; color: var(--green-600); }
    .submit { width: 100%; min-height: 47px; display: flex; align-items: center; justify-content: center; gap: 9px; padding: 0 17px; color: #fff; background: var(--green-700); border: 1px solid var(--green-700); border-radius: 7px; font-size: 10px; font-weight: 800; cursor: pointer; box-shadow: 0 7px 17px rgba(29, 107, 76, .17); transition: transform .18s, background .18s, box-shadow .18s; }
    .submit:hover { background: var(--green-800); transform: translateY(-1px); box-shadow: 0 10px 21px rgba(29, 107, 76, .21); }
    .submit svg { width: 15px; height: 15px; }
    .back { display: flex; align-items: center; justify-content: center; gap: 7px; width: max-content; margin: 24px auto 0; color: var(--muted); font-size: 9px; font-weight: 600; text-decoration: none; }
    .back:hover { color: var(--green-700); }
    .back svg { width: 13px; height: 13px; }
    .form-note { margin: 32px 0 0; padding-top: 20px; color: var(--quiet); border-top: 1px solid var(--line); font-size: 8px; line-height: 1.65; text-align: center; }

    @media (max-width: 980px) {
        .auth-shell { grid-template-columns: minmax(360px, .8fr) minmax(450px, 1.2fr); }
        .auth-showcase { padding-inline: 40px; }
        .auth-area { padding: 40px; }
    }
    @media (max-width: 760px) {
        .auth-shell { display: block; }
        .auth-showcase { display: none; }
        .auth-area { min-height: 100vh; padding: 35px 24px; }
        .mobile-brand { display: flex; align-items: center; gap: 10px; width: max-content; margin: 0 0 45px; color: var(--green-950); text-decoration: none; }
        .mobile-brand img { width: 39px; height: 39px; object-fit: cover; border: 3px solid var(--green-100); border-radius: 50%; }
        .mobile-brand strong, .mobile-brand span { display: block; }
        .mobile-brand strong { font-size: 11px; }
        .mobile-brand span { margin-top: 3px; color: var(--muted); font-size: 7px; text-transform: uppercase; letter-spacing: .06em; }
    }
    @media (max-width: 430px) {
        .auth-area { align-items: start; padding: 25px 18px 30px; }
        .mobile-brand { margin-bottom: 37px; }
        .auth-panel h1 { font-size: 30px; }
        .auth-subtitle { margin-bottom: 25px; }
        .secure-copy { display: none; }
        .form-options { justify-content: flex-start; }
    }

    /* Legibility scale shared by admin and student login pages. */
    .showcase-brand strong { font-size: 15px; }
    .showcase-brand span { font-size: 9.5px; }
    .showcase-kicker { font-size: 10.5px; }
    .showcase-content > p { font-size: 14px; }
    .showcase-point { font-size: 12px; }
    .showcase-point i { width: 31px; height: 31px; }
    .showcase-point svg { width: 15px; height: 15px; }
    .showcase-footer { font-size: 10px; }
    .form-badge { font-size: 10px; }
    .auth-subtitle { font-size: 13px; }
    .auth-error { font-size: 11px; }
    .auth-error svg { width: 17px; height: 17px; }
    .field label { font-size: 11px; }
    .field input { font-size: 13px; }
    .field-icon { width: 17px; height: 17px; }
    .password-toggle { width: 35px; height: 35px; }
    .password-toggle svg { width: 17px; height: 17px; }
    .remember { font-size: 11px; }
    .remember input { width: 16px; height: 16px; }
    .secure-copy { font-size: 10px; }
    .secure-copy svg { width: 14px; height: 14px; }
    .submit { min-height: 49px; font-size: 12px; }
    .submit svg { width: 17px; height: 17px; }
    .back { font-size: 11px; }
    .back svg { width: 15px; height: 15px; }
    .form-note { font-size: 10px; }
    .mobile-brand strong { font-size: 13px; }
    .mobile-brand span { font-size: 9px; }

    /* Keep the remember-me checkbox visually quiet like checkboxes inside the system. */
    .remember input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        display: inline-grid;
        place-content: center;
        width: 16px;
        height: 16px;
        margin: 0;
        background: #fbfcfd;
        border: 1px solid #b8c0cc;
        border-radius: 4px;
        opacity: .82;
        cursor: pointer;
        transition: background .15s ease, border-color .15s ease, box-shadow .15s ease, opacity .15s ease;
    }
    .remember input[type="checkbox"]::before {
        content: "";
        width: 9px;
        height: 9px;
        background: #fff;
        clip-path: polygon(14% 44%, 0 60%, 39% 100%, 100% 17%, 84% 0, 38% 62%);
        transform: scale(0);
        transition: transform .12s ease;
    }
    .remember input[type="checkbox"]:checked {
        background: #8792a8;
        border-color: #7f8aa0;
        opacity: .86;
    }
    .remember input[type="checkbox"]:checked::before { transform: scale(1); }
    .remember input[type="checkbox"]:hover { opacity: .96; }
    .remember input[type="checkbox"]:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(135, 146, 168, .16);
        opacity: 1;
    }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { transition-duration: .01ms !important; }
    }
</style>
