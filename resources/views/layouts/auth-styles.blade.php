<style>
    * { box-sizing: border-box; }
    body {
        min-height: 100vh;
        margin: 0;
        display: grid;
        place-items: center;
        padding: 24px;
        color: #101828;
        background: #f7f8fa;
        font-family: "Inter", system-ui, sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .auth-panel {
        width: min(100%, 400px);
        padding: 28px;
        background: #fff;
        border: 1px solid #e4e7ec;
        border-radius: 10px;
    }
    .auth-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 32px;
        color: #101828;
        text-decoration: none;
    }
    .auth-brand img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
    }
    .auth-brand strong,
    .auth-brand small { display: block; }
    .auth-brand strong { font-size: 12px; }
    .auth-brand small {
        margin-top: 2px;
        color: #667085;
        font-size: 9px;
    }
    h1 {
        margin: 0;
        font-size: 25px;
        letter-spacing: -.04em;
    }
    .auth-subtitle {
        margin: 8px 0 26px;
        color: #667085;
        font-size: 12px;
    }
    .auth-error {
        margin-bottom: 18px;
        padding: 10px 12px;
        color: #b42318;
        background: #fff5f5;
        border: 1px solid #fecaca;
        border-radius: 7px;
        font-size: 11px;
    }
    .field { margin-bottom: 16px; }
    label {
        display: block;
        margin-bottom: 6px;
        color: #344054;
        font-size: 11px;
        font-weight: 600;
    }
    input[type="text"],
    input[type="password"] {
        width: 100%;
        height: 42px;
        padding: 0 11px;
        color: #101828;
        background: #fff;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        font: inherit;
        font-size: 12px;
        outline: none;
    }
    input:focus {
        border-color: #2878f0;
        box-shadow: 0 0 0 3px rgba(40, 120, 240, .12);
    }
    .remember {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 4px 0 20px;
        color: #667085;
        font-size: 11px;
    }
    .remember label { margin: 0; font-weight: 500; }
    .submit {
        width: 100%;
        min-height: 42px;
        color: #fff;
        background: #2878f0;
        border: 1px solid #2878f0;
        border-radius: 7px;
        font: inherit;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }
    .submit:hover { background: #1768dc; }
    .back {
        display: block;
        margin-top: 20px;
        color: #667085;
        font-size: 11px;
        text-align: center;
        text-decoration: none;
    }
    .back:hover { color: #2878f0; }
</style>
