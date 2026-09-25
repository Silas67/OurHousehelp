<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutConfirmModal" tabindex="-1"
     aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content ohh-logout-modal">

            <div class="modal-body text-center p-4">

                <div class="ohh-logout-icon">
                    <i class="fas fa-right-from-bracket"></i>
                </div>

                <h4 id="logoutConfirmModalLabel" class="ohh-logout-title">
                    Are you sure you want to log out?
                </h4>

                <p class="ohh-logout-text">
                    You will be signed out of your HouseHelp account.
                </p>

                <div class="d-flex gap-2 justify-content-center mt-4">

                    <button type="button"
                            class="btn ohh-logout-cancel"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="button"
                            class="btn ohh-logout-confirm"
                            onclick="document.getElementById('logout-form-nav').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Log Out
                    </button>

                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .ohh-logout-modal {
        border: 1px solid #E5DED0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 55px rgba(22, 36, 31, 0.16);
    }

    .ohh-logout-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: #F8EEEE;
        color: #A94442;
        font-size: 21px;
    }

    .ohh-logout-title {
        margin-bottom: 8px;
        font-family: Georgia, serif;
        font-size: 23px;
        font-weight: 500;
        color: #18352D;
    }

    .ohh-logout-text {
        margin: 0;
        color: #727C77;
        font-size: 14px;
        line-height: 1.6;
    }

    .ohh-logout-cancel,
    .ohh-logout-confirm {
        min-width: 120px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .ohh-logout-cancel {
        border: 1px solid #D8D0C1;
        background: #fff;
        color: #43514B;
    }

    .ohh-logout-cancel:hover {
        background: #F5F1E8;
        color: #18352D;
    }

    .ohh-logout-confirm {
        border: 1px solid #A94442;
        background: #A94442;
        color: #fff;
    }

    .ohh-logout-confirm:hover {
        border-color: #963B39;
        background: #963B39;
        color: #fff;
    }
</style>