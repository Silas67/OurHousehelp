@extends('layouts.usermain')
@section('profile')
    <div class="container mt-5">
        <!-- Profile Section -->
        <div class="card">
            <div class="card-header text-white recentapp">
                <h5>Profile</h5>
            </div>
            @include('partials._profileupdate')
        </div>
    </div>

    @include('partials._changepassword')

    <!-- Floating Notification Container -->
    <div id="notificationContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

    <script>
        function previewImage(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }

        // Notification System
        class FloatingNotification {
            constructor() {
                this.container = document.getElementById('notificationContainer');
                this.notifications = [];
                this.autoHideDuration = 5000; // 5 seconds
            }

            show(type, message, title = null, autoHide = true) {
                const notificationId = 'notification-' + Date.now();
                const typeConfig = {
                    success: {
                        class: 'notification-success',
                        icon: 'fas fa-check-circle',
                        titleText: title || 'Success!',
                        buttonClass: 'btn-ok-success'
                    },
                    error: {
                        class: 'notification-error',
                        icon: 'fas fa-exclamation-circle',
                        titleText: title || 'Error!',
                        buttonClass: 'btn-ok-error'
                    },
                    warning: {
                        class: 'notification-warning',
                        icon: 'fas fa-exclamation-triangle',
                        titleText: title || 'Warning!',
                        buttonClass: 'btn-ok-warning'
                    },
                    info: {
                        class: 'notification-info',
                        icon: 'fas fa-info-circle',
                        titleText: title || 'Information',
                        buttonClass: 'btn-ok-info'
                    }
                };

                const config = typeConfig[type] || typeConfig.info;

                const notificationHtml = `
                    <div id="${notificationId}" class="floating-notification ${config.class}" style="min-width: 300px; max-width: 400px; animation: slideIn 0.3s ease-out; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 8px; overflow: hidden; margin-bottom: 10px;">
                        <div class="notification-header" style="padding: 12px 15px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(0,0,0,0.1);">
                            <div class="notification-title" style="display: flex; align-items: center; gap: 8px; font-weight: 600;">
                                <i class="${config.icon}"></i>
                                <span>${config.titleText}</span>
                            </div>
                            <button type="button" class="notification-close" onclick="notificationSystem.close('${notificationId}')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #6c757d; padding: 0; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 4px; transition: background-color 0.2s;">
                                &times;
                            </button>
                        </div>
                        <div class="notification-body" style="padding: 15px; font-size: 14px; line-height: 1.5;">
                            ${message}
                        </div>
                        <div class="notification-actions" style="padding: 10px 15px 15px; text-align: right;">
                            <button type="button" class="btn-ok ${config.buttonClass}" onclick="notificationSystem.close('${notificationId}')" style="padding: 6px 20px; font-size: 14px; border-radius: 4px; border: none; cursor: pointer; font-weight: 500; transition: all 0.2s;">
                                OK
                            </button>
                        </div>
                    </div>
                `;

                this.container.insertAdjacentHTML('beforeend', notificationHtml);
                
                const notification = {
                    id: notificationId,
                    element: document.getElementById(notificationId),
                    timeout: null
                };

                this.notifications.push(notification);

                if (autoHide) {
                    notification.timeout = setTimeout(() => {
                        this.close(notificationId);
                    }, this.autoHideDuration);
                }

                return notificationId;
            }

            close(notificationId) {
                const notification = this.notifications.find(n => n.id === notificationId);
                if (!notification) return;

                if (notification.timeout) {
                    clearTimeout(notification.timeout);
                }

                notification.element.style.animation = 'slideOut 0.3s ease-out forwards';
                
                setTimeout(() => {
                    notification.element.remove();
                    this.notifications = this.notifications.filter(n => n.id !== notificationId);
                }, 300);
            }

            closeAll() {
                this.notifications.forEach(notification => {
                    this.close(notification.id);
                });
            }

            success(message, title = null, autoHide = true) {
                return this.show('success', message, title, autoHide);
            }

            error(message, title = null, autoHide = true) {
                return this.show('error', message, title, autoHide);
            }

            warning(message, title = null, autoHide = true) {
                return this.show('warning', message, title, autoHide);
            }

            info(message, title = null, autoHide = true) {
                return this.show('info', message, title, autoHide);
            }
        }

        // Initialize notification system
        const notificationSystem = new FloatingNotification();

        // Add CSS for animations and styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            
            .notification-success {
                background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
                border-left: 4px solid #28a745;
            }
            
            .notification-error {
                background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
                border-left: 4px solid #dc3545;
            }
            
            .notification-warning {
                background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
                border-left: 4px solid #ffc107;
            }
            
            .notification-info {
                background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
                border-left: 4px solid #17a2b8;
            }
            
            .btn-ok-success {
                background-color: #28a745;
                color: white;
            }
            
            .btn-ok-success:hover {
                background-color: #218838;
            }
            
            .btn-ok-error {
                background-color: #dc3545;
                color: white;
            }
            
            .btn-ok-error:hover {
                background-color: #c82333;
            }
            
            .btn-ok-warning {
                background-color: #ffc107;
                color: #212529;
            }
            
            .btn-ok-warning:hover {
                background-color: #e0a800;
            }
            
            .btn-ok-info {
                background-color: #17a2b8;
                color: white;
            }
            
            .btn-ok-info:hover {
                background-color: #138496;
            }
            
            .notification-close:hover {
                background-color: rgba(0,0,0,0.1);
            }
        `;
        document.head.appendChild(style);

        // Check for Laravel session messages
        document.addEventListener('DOMContentLoaded', function() {
            // Laravel session messages
            @if(session('success'))
                notificationSystem.success('{{ session("success") }}');
            @endif

            @if(session('error'))
                notificationSystem.error('{{ session("error") }}');
            @endif

            @if(session('warning'))
                notificationSystem.warning('{{ session("warning") }}');
            @endif

            @if(session('info'))
                notificationSystem.info('{{ session("info") }}');
            @endif

            // Laravel validation errors
            @if($errors->any())
                @foreach($errors->all() as $error)
                    notificationSystem.error('{{ $error }}');
                @endforeach
            @endif

            // Form submission success messages
            @if(session('profile_updated'))
                notificationSystem.success('{{ session("profile_updated") }}');
            @endif

            @if(session('password_changed'))
                notificationSystem.success('{{ session("password_changed") }}');
            @endif

            @if(session('profile_error'))
                notificationSystem.error('{{ session("profile_error") }}');
            @endif

            @if(session('password_error'))
                notificationSystem.error('{{ session("password_error") }}');
            @endif
        });

        // Keyboard support - Escape to close all
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                notificationSystem.closeAll();
            }
        });

        // Global functions for AJAX responses
        window.showSuccess = notificationSystem.success.bind(notificationSystem);
        window.showError = notificationSystem.error.bind(notificationSystem);
        window.showWarning = notificationSystem.warning.bind(notificationSystem);
        window.showInfo = notificationSystem.info.bind(notificationSystem);
    </script>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection