<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0
    }

    .mail-bg {
        background: #0f172a;
        padding: 2.5rem 1rem;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .mail-wrap {
        max-width: 560px;
        margin: 0 auto;
    }

    .mail-logo {
        text-align: center;
        padding-bottom: 1.5rem;
    }

    .mail-logo-inner {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .logo-box {
        width: 40px;
        height: 40px;
        background: #0072c6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-box svg {
        width: 22px;
        height: 22px;
        fill: none;
        stroke: #fff;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round
    }

    .logo-text {
        font-size: 17px;
        font-weight: 600;
        color: #e2eaf4;
        letter-spacing: 0.02em
    }

    .mail-card {
        background: #0d1f35;
        border: 1px solid rgba(0, 200, 255, 0.12);
        border-radius: 16px;
        overflow: hidden;
    }

    .mail-header {
        background: linear-gradient(135deg, #0c2a4a 0%, #0a1e38 100%);
        padding: 2.5rem 2.5rem 2rem;
        border-bottom: 1px solid rgba(0, 200, 255, 0.1);
        text-align: center;
    }

    .mail-icon-ring {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(0, 200, 255, 0.08);
        border: 1px solid rgba(0, 200, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
    }

    .mail-icon-ring svg {
        width: 28px;
        height: 28px;
        stroke: #00c8ff;
        stroke-width: 1.8;
        fill: none;
        stroke-linecap: round;
        stroke-linejoin: round
    }

    .mail-header h1 {
        font-size: 20px;
        font-weight: 600;
        color: #e2eaf4;
        margin-bottom: 0.5rem;
        letter-spacing: 0.01em;
    }

    .mail-header p {
        font-size: 13.5px;
        color: #7a90a8;
        line-height: 1.6;
    }

    .mail-body {
        padding: 2rem 2.5rem;
    }

    .greeting {
        font-size: 14px;
        color: #7a90a8;
        margin-bottom: 1rem;
        line-height: 1.7;
    }

    .greeting strong {
        color: #b0c4d8
    }

    .cta-wrap {
        text-align: center;
        margin: 1.75rem 0;
    }

    .cta-btn {
        display: inline-block;
        background: #0072c6;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        padding: 13px 36px;
        border-radius: 10px;
        text-decoration: none;
        letter-spacing: 0.02em;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }

    .info-box {
        background: rgba(245, 158, 11, 0.06);
        border: 1px solid rgba(245, 158, 11, 0.18);
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin: 1.5rem 0;
    }

    .info-box svg {
        width: 16px;
        height: 16px;
        stroke: #f59e0b;
        stroke-width: 2;
        fill: none;
        flex-shrink: 0;
        margin-top: 1px;
        stroke-linecap: round;
        stroke-linejoin: round
    }

    .info-box p {
        font-size: 12.5px;
        color: #b08020;
        line-height: 1.6
    }

    .info-box p strong {
        color: #f59e0b
    }

    .mail-divider {
        height: 1px;
        background: rgba(0, 200, 255, 0.08);
        margin: 1.5rem 0;
    }

    .ignore-note {
        font-size: 12.5px;
        color: #4a6278;
        line-height: 1.7;
    }

    .ignore-note strong {
        color: #6a8298
    }

    .mail-footer {
        background: #071523;
        border-top: 1px solid rgba(0, 200, 255, 0.08);
        padding: 1.5rem 2.5rem;
    }

    .footer-fallback {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        padding: 12px 14px;
        margin-bottom: 1.25rem;
    }

    .footer-fallback p {
        font-size: 11.5px;
        color: #4a6278;
        line-height: 1.6;
        margin-bottom: 6px
    }

    .footer-fallback .url {
        font-size: 11px;
        color: #1a8fe3;
        word-break: break-all;
        line-height: 1.5;
        font-family: 'Courier New', monospace;
    }

    .footer-meta {
        text-align: center;
        font-size: 11.5px;
        color: #304558;
        line-height: 1.6;
    }

    .footer-meta a {
        color: #4a6278;
        text-decoration: none
    }

    .footer-sep {
        margin: 0 6px;
        color: #1e3245
    }

    .regards {
        font-size: 13.5px;
        color: #7a90a8;
        margin-bottom: 0.25rem;
    }

    .regards-name {
        font-size: 14px;
        font-weight: 600;
        color: #00c8ff;
    }
</style>

<div class="mail-bg">
    <div class="mail-wrap">
        <div class="mail-logo">
            <div class="mail-logo-inner">
                <div class="logo-box">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" />
                        <path d="M2 17l10 5 10-5" />
                        <path d="M2 12l10 5 10-5" />
                    </svg>
                </div>
                <span class="logo-text">ProjectHub</span>
            </div>
        </div>

        <div class="mail-card">
            <div class="mail-header">
                <div class="mail-icon-ring">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                </div>
                <h1>Reset your password</h1>
                <p>We received a request to reset the password<br>associated with your account.</p>
            </div>

            <div class="mail-body">
                <p class="greeting">
                    Hello, <strong>{{ $notifiable->name }}!</strong><br><br>
                    You are receiving this email because we received a password reset request for your account. Click
                    the button below to create a new password.
                </p>

                <div class="cta-wrap">
                    <a href="{{ $url }}" class="cta-btn">Reset Password</a>
                </div>

                <div class="info-box">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    <p>This password reset link will expire in <strong>60 minutes</strong>. Please reset your password
                        before the link expires.</p>
                </div>

                <div class="mail-divider"></div>

                <p class="ignore-note">
                    If you did not request a password reset, <strong>no further action is required</strong>. You can
                    safely ignore this email — your password will not be changed.
                </p>

                <div class="mail-divider"></div>

                {{-- <p class="regards">Regards,</p>
                <p class="regards-name">ProjectHub Team</p> --}}
            </div>

            <div class="mail-footer">
                <div class="footer-fallback">
                    <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into
                        your web browser:</p>
                    <span
                        class="url">https://project-management-v2.test/reset-password/60ec53ef868906df6e62463a9ea0d77d61564a3f3e7aae704a5bd101341a4877?email=anggota%40gmail.com</span>
                </div>
                <div class="footer-meta">
                    &copy; 2026 ProjectHub. All rights reserved.<br>
                    <a href="#">Unsubscribe</a><span class="footer-sep">·</span><a href="#">Privacy
                        Policy</a><span class="footer-sep">·</span><a href="#">Help Center</a>
                </div>
            </div>
        </div>
    </div>
</div>
