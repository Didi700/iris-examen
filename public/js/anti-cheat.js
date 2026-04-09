(function() {
    'use strict';

    let tabChanges = 0;
    let pasteAttempts = 0;
    let sessionId = null;

    // Initialiser avec l'ID de session
    function init(examSessionId) {
        sessionId = examSessionId;
        setupEventListeners();
    }

    // Configurer les écouteurs d'événements
    function setupEventListeners() {
        // Détection de changement d'onglet
        document.addEventListener('visibilitychange', handleVisibilityChange);
        
        // Détection de changement de focus
        window.addEventListener('blur', handleWindowBlur);
        
        // Détection de copier-coller
        document.addEventListener('paste', handlePaste);
        
        // Détection de clic droit
        document.addEventListener('contextmenu', handleContextMenu);
        
        // Détection de raccourcis clavier suspects
        document.addEventListener('keydown', handleKeyDown);
        
        // Détection de screenshot (limité)
        detectScreenshot();
    }

    // Gérer le changement de visibilité
    function handleVisibilityChange() {
        if (document.hidden) {
            tabChanges++;
            reportEvent('tab_change', {
                count: tabChanges,
                timestamp: new Date().toISOString()
            });
            
            // Avertir l'utilisateur
            showWarning('⚠️ Attention ! Le changement d\'onglet a été détecté.');
        }
    }

    // Gérer la perte de focus
    function handleWindowBlur() {
        reportEvent('window_blur', {
            timestamp: new Date().toISOString()
        });
    }

    // Gérer le copier-coller
    function handlePaste(e) {
        pasteAttempts++;
        
        // Bloquer le paste dans les zones de réponse
        if (e.target.classList.contains('no-paste')) {
            e.preventDefault();
            showWarning('⛔ Le copier-coller est désactivé pour cet examen.');
        }
        
        reportEvent('paste_detected', {
            count: pasteAttempts,
            timestamp: new Date().toISOString()
        });
    }

    // Gérer le clic droit
    function handleContextMenu(e) {
        // Bloquer le clic droit sur l'examen
        if (e.target.closest('.exam-content')) {
            e.preventDefault();
            showWarning('⛔ Le clic droit est désactivé pendant l\'examen.');
        }
    }

    // Gérer les raccourcis clavier
    function handleKeyDown(e) {
        // Bloquer F12 (DevTools)
        if (e.keyCode === 123) {
            e.preventDefault();
            reportEvent('devtools_attempt', {
                timestamp: new Date().toISOString()
            });
        }
        
        // Bloquer Ctrl+U (voir source)
        if (e.ctrlKey && e.keyCode === 85) {
            e.preventDefault();
            reportEvent('view_source_attempt', {
                timestamp: new Date().toISOString()
            });
        }
        
        // Bloquer Ctrl+Shift+I (DevTools)
        if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
            e.preventDefault();
            reportEvent('devtools_attempt', {
                timestamp: new Date().toISOString()
            });
        }
        
        // Bloquer Ctrl+Shift+C (Inspect)
        if (e.ctrlKey && e.shiftKey && e.keyCode === 67) {
            e.preventDefault();
            reportEvent('inspect_attempt', {
                timestamp: new Date().toISOString()
            });
        }
        
        // Bloquer Print Screen
        if (e.keyCode === 44) {
            reportEvent('screenshot_attempt', {
                timestamp: new Date().toISOString()
            });
        }
    }

    // Détecter les screenshots (méthode limitée)
    function detectScreenshot() {
        // Cette méthode est limitée mais peut détecter certains cas
        setInterval(() => {
            if (navigator.clipboard) {
                navigator.clipboard.readText().then(() => {
                    // Clipboard accessible - possibilité de screenshot
                }).catch(() => {
                    // Normal
                });
            }
        }, 5000);
    }

    // Afficher un avertissement
    function showWarning(message) {
        // Créer une notification toast
        const warning = document.createElement('div');
        warning.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-bounce';
        warning.textContent = message;
        
        document.body.appendChild(warning);
        
        setTimeout(() => {
            warning.remove();
        }, 5000);
    }

    // Rapporter un événement au serveur
    function reportEvent(type, data) {
        fetch('/api/anti-cheat/report', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                session_id: sessionId,
                event_type: type,
                data: data
            })
        }).catch(error => {
            console.error('Erreur lors du rapport:', error);
        });
    }

    // Obtenir les statistiques de triche
    function getStats() {
        return {
            tabChanges: tabChanges,
            pasteAttempts: pasteAttempts
        };
    }

    // Exposer l'API publique
    window.AntiCheat = {
        init: init,
        getStats: getStats
    };
})();