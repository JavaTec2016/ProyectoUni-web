<style>
    :root {
        --input-bg: #f3f4f6;
        --input-border: #d1d5db;
    }

    .carousel {
        width: 70%;
        margin: 50px auto;
    }

    #footer {
        background-color: white;
    }

    #body {
        background-color: white;
    }

    .outliner {
        outline-width: 4px;
    }

    .scale {
        transition: transform .25s, background .25s;
    }

    .scale:hover {
        transform: scale(0.9);
    }

    .evento-feed {
        transform: scale(0.8);
        transition: transform .25s, background .25s;
    }

    .evento-feed:hover {
        transform: scale(0.9);
        cursor: pointer;
    }

    .login-container {
        background-color: white;
        padding: 40px 30px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        max-width: 400px;
        width: 100%;
    }

    .login-container h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 24px;
    }

    .form-group {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--input-border);
        border-radius: 8px;
        background-color: var(--input-bg);
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .body-center {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .fit-screen {
        position: absolute;
        overflow: visible;
        max-width: 100%;
        max-height: 100%;
        width: 100%;
        height: 100%;
    }

    .scroller {
        overflow-x: auto;
        overflow-y: auto;
        width: 100%;
        height: 100%;
    }

    .no-overflow {
        overflow: hidden;
    }

    .abcc {
        padding: 0.5rem;
    }

    .abcc_opcion {
        align-items: center;
        border-radius: 9999px;
        cursor: pointer;
        display: flex;
        height: 4rem;
        position: relative;

        width: 100%;
        

        object-fit: cover;
    }
    
    .abcc_opcion:hover {
        background-color: rgba(0,0,0,.15);
    }

    .abcc_opcion img {
        display: inline-block;
        border-radius: 50%;
        max-width: 5rem;

    }

    .abcc_opcion p {
        display: block;
        width: 50%;
        margin: 0;
    }

    .fix {
        position: fixed;
    }

    .side-nav {
        height: 100vh;
        width: 20rem;
        flex-grow: 0;
        flex-shrink: 0;

    }
</style>