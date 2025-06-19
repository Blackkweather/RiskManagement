// Enhanced Report Management JavaScript
class ReportManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadReports();
        this.initializeCharts();
    }

    bindEvents() {
        // Download buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('.download-btn')) {
                this.handleDownload(e.target);
            }
            if (e.target.matches('.share-btn')) {
                this.handleShare(e.target);
            }
            if (e.target.matches('.schedule-btn')) {
                this.handleSchedule(e.target);
            }
        });

        // Search and filter
        const searchInput = document.getElementById('reportSearch');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.filterReports(e.target.value);
            });
        }

        const filterSelect = document.getElementById('reportFilter');
        if (filterSelect) {
            filterSelect.addEventListener('change', (e) => {
                this.filterByType(e.target.value);
            });
        }

        // Report builder modal
        const generateBtn = document.querySelector('.btn-generate-report');
        if (generateBtn) {
            generateBtn.addEventListener('click', () => {
                this.openReportBuilder();
            });
        }
    }

    async handleDownload(button) {
        const reportId = button.dataset.reportId;
        const format = button.dataset.format || 'pdf';
        
        // Show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Téléchargement...';
        
        try {
            // Track download analytics
            this.trackDownload(reportId, format);
            
            // Create download link
            const downloadUrl = `enhanced_download_report.php?id=${reportId}&format=${format}`;
            
            // For large reports, show progress
            if (format === 'excel' || format === 'word') {
                await this.downloadWithProgress(downloadUrl, button);
            } else {
                window.location.href = downloadUrl;
            }
            
            // Update download count in UI
            this.updateDownloadCount(reportId);
            
            // Show success notification
            this.showNotification('Rapport téléchargé avec succès', 'success');
            
        } catch (error) {
            console.error('Download error:', error);
            this.showNotification('Erreur lors du téléchargement', 'error');
        } finally {
            // Restore button state
            button.disabled = false;
            button.innerHTML = `<i class="fas fa-download"></i> ${format.toUpperCase()}`;
        }
    }

    async downloadWithProgress(url, button) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            
            xhr.open('GET', url, true);
            xhr.responseType = 'blob';
            
            xhr.onprogress = (e) => {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${Math.round(percentComplete)}%`;
                }
            };
            
            xhr.onload = () => {
                if (xhr.status === 200) {
                    const blob = xhr.response;
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = xhr.getResponseHeader('Content-Disposition')?.split('filename=')[1] || 'report';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                    resolve();
                } else {
                    reject(new Error('Download failed'));
                }
            };
            
            xhr.onerror = () => reject(new Error('Network error'));
            xhr.send();
        });
    }

    handleShare(button) {
        const reportId = button.dataset.reportId;
        const reportName = button.dataset.reportName;
        
        // Open share modal
        const modal = this.createShareModal(reportId, reportName);
        document.body.appendChild(modal);
        modal.style.display = 'flex';
    }

    createShareModal(reportId, reportName) {
        const modal = document.createElement('div');
        modal.className = 'modal share-modal';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Partager le Rapport</h3>
                    <button class="close-btn" onclick="this.closest('.modal').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>${reportName}</h4>
                    <div class="share-options">
                        <div class="share-option">
                            <label>Email:</label>
                            <input type="email" id="shareEmail" placeholder="email@example.com">
                        </div>
                        <div class="share-option">
                            <label>Niveau d'accès:</label>
                            <select id="sharePermission">
                                <option value="view">Lecture seule</option>
                                <option value="download">Téléchargement</option>
                                <option value="edit">Modification</option>
                            </select>
                        </div>
                        <div class="share-option">
                            <label>Expiration:</label>
                            <select id="shareExpiry">
                                <option value="">Jamais</option>
                                <option value="7">7 jours</option>
                                <option value="30">30 jours</option>
                                <option value="90">90 jours</option>
                            </select>
                        </div>
                        <div class="share-option">
                            <label>
                                <input type="checkbox" id="shareNotify" checked>
                                Notifier par email
                            </label>
                        </div>
                    </div>
                    <div class="share-link-section">
                        <h5>Lien de partage:</h5>
                        <div class="link-container">
                            <input type="text" id="shareLink" readonly 
                                   value="https://riskmanagement.app/shared/${reportId}/${this.generateShareToken()}">
                            <button onclick="this.previousElementSibling.select(); document.execCommand('copy'); this.textContent='Copié!'">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="this.closest('.modal').remove()">Annuler</button>
                    <button class="btn btn-primary" onclick="reportManager.shareReport(${reportId})">Partager</button>
                </div>
            </div>
        `;
        return modal;
    }

    async shareReport(reportId) {
        const email = document.getElementById('shareEmail').value;
        const permission = document.getElementById('sharePermission').value;
        const expiry = document.getElementById('shareExpiry').value;
        const notify = document.getElementById('shareNotify').checked;
        
        try {
            const response = await fetch('api/share_report.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    reportId,
                    email,
                    permission,
                    expiry,
                    notify
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showNotification('Rapport partagé avec succès', 'success');
                document.querySelector('.share-modal').remove();
            } else {
                this.showNotification(result.message || 'Erreur lors du partage', 'error');
            }
        } catch (error) {
            console.error('Share error:', error);
            this.showNotification('Erreur lors du partage', 'error');
        }
    }

    handleSchedule(button) {
        const reportId = button.dataset.reportId;
        const modal = this.createScheduleModal(reportId);
        document.body.appendChild(modal);
        modal.style.display = 'flex';
    }

    createScheduleModal(reportId) {
        const modal = document.createElement('div');
        modal.className = 'modal schedule-modal';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Programmer la Génération</h3>
                    <button class="close-btn" onclick="this.closest('.modal').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="schedule-options">
                        <div class="schedule-option">
                            <label>Fréquence:</label>
                            <select id="scheduleFrequency">
                                <option value="daily">Quotidien</option>
                                <option value="weekly">Hebdomadaire</option>
                                <option value="monthly">Mensuel</option>
                                <option value="quarterly">Trimestriel</option>
                                <option value="yearly">Annuel</option>
                            </select>
                        </div>
                        <div class="schedule-option">
                            <label>Heure:</label>
                            <input type="time" id="scheduleTime" value="09:00">
                        </div>
                        <div class="schedule-option">
                            <label>Format:</label>
                            <select id="scheduleFormat">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                                <option value="word">Word</option>
                            </select>
                        </div>
                        <div class="schedule-option">
                            <label>Destinataires:</label>
                            <textarea id="scheduleRecipients" placeholder="email1@example.com, email2@example.com"></textarea>
                        </div>
                        <div class="schedule-option">
                            <label>
                                <input type="checkbox" id="scheduleActive" checked>
                                Activer la programmation
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="this.closest('.modal').remove()">Annuler</button>
                    <button class="btn btn-primary" onclick="reportManager.scheduleReport(${reportId})">Programmer</button>
                </div>
            </div>
        `;
        return modal;
    }

    async scheduleReport(reportId) {
        const frequency = document.getElementById('scheduleFrequency').value;
        const time = document.getElementById('scheduleTime').value;
        const format = document.getElementById('scheduleFormat').value;
        const recipients = document.getElementById('scheduleRecipients').value;
        const active = document.getElementById('scheduleActive').checked;
        
        try {
            const response = await fetch('api/schedule_report.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    reportId,
                    frequency,
                    time,
                    format,
                    recipients: recipients.split(',').map(email => email.trim()),
                    active
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showNotification('Programmation configurée avec succès', 'success');
                document.querySelector('.schedule-modal').remove();
            } else {
                this.showNotification(result.message || 'Erreur lors de la programmation', 'error');
            }
        } catch (error) {
            console.error('Schedule error:', error);
            this.showNotification('Erreur lors de la programmation', 'error');
        }
    }

    filterReports(searchTerm) {
        const reports = document.querySelectorAll('.report-card');
        const term = searchTerm.toLowerCase();
        
        reports.forEach(report => {
            const title = report.querySelector('.report-title').textContent.toLowerCase();
            const description = report.querySelector('.report-description').textContent.toLowerCase();
            const type = report.querySelector('.report-meta').textContent.toLowerCase();
            
            if (title.includes(term) || description.includes(term) || type.includes(term)) {
                report.style.display = 'block';
            } else {
                report.style.display = 'none';
            }
        });
    }

    filterByType(type) {
        const reports = document.querySelectorAll('.report-card');
        
        reports.forEach(report => {
            if (type === 'all' || report.dataset.type === type) {
                report.style.display = 'block';
            } else {
                report.style.display = 'none';
            }
        });
    }

    trackDownload(reportId, format) {
        // Send analytics data
        fetch('api/track_download.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                reportId,
                format,
                timestamp: new Date().toISOString(),
                userAgent: navigator.userAgent
            })
        }).catch(error => console.error('Analytics error:', error));
    }

    updateDownloadCount(reportId) {
        const countElement = document.querySelector(`[data-report-id="${reportId}"] .download-count`);
        if (countElement) {
            const currentCount = parseInt(countElement.textContent) || 0;
            countElement.textContent = currentCount + 1;
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    generateShareToken() {
        return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    }

    openReportBuilder() {
        // Implementation for report builder modal
        const modal = this.createReportBuilderModal();
        document.body.appendChild(modal);
        modal.style.display = 'flex';
    }

    createReportBuilderModal() {
        const modal = document.createElement('div');
        modal.className = 'modal report-builder-modal';
        modal.innerHTML = `
            <div class="modal-content large">
                <div class="modal-header">
                    <h3>Générateur de Rapports</h3>
                    <button class="close-btn" onclick="this.closest('.modal').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="builder-steps">
                        <div class="step active" data-step="1">
                            <h4>1. Type de Rapport</h4>
                            <div class="report-types">
                                <div class="type-option" data-type="risk">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <h5>Évaluation des Risques</h5>
                                    <p>Analyse complète des risques identifiés</p>
                                </div>
                                <div class="type-option" data-type="compliance">
                                    <i class="fas fa-shield-check"></i>
                                    <h5>Conformité</h5>
                                    <p>Rapport de conformité réglementaire</p>
                                </div>
                                <div class="type-option" data-type="financial">
                                    <i class="fas fa-chart-line"></i>
                                    <h5>Impact Financier</h5>
                                    <p>Analyse des impacts financiers</p>
                                </div>
                                <div class="type-option" data-type="security">
                                    <i class="fas fa-lock"></i>
                                    <h5>Sécurité</h5>
                                    <p>Évaluation de la sécurité</p>
                                </div>
                                <div class="type-option" data-type="executive">
                                    <i class="fas fa-chart-pie"></i>
                                    <h5>Exécutif</h5>
                                    <p>Tableau de bord pour la direction</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="step" data-step="2">
                            <h4>2. Configuration</h4>
                            <div class="config-options">
                                <div class="form-group">
                                    <label>Nom du rapport:</label>
                                    <input type="text" id="reportName" placeholder="Nom du rapport">
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea id="reportDescription" placeholder="Description du rapport"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Période:</label>
                                    <select id="reportPeriod">
                                        <option value="current">Période actuelle</option>
                                        <option value="last_month">Mois dernier</option>
                                        <option value="last_quarter">Trimestre dernier</option>
                                        <option value="last_year">Année dernière</option>
                                        <option value="custom">Période personnalisée</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Format:</label>
                                    <div class="format-options">
                                        <label><input type="checkbox" value="pdf" checked> PDF</label>
                                        <label><input type="checkbox" value="excel"> Excel</label>
                                        <label><input type="checkbox" value="word"> Word</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="step" data-step="3">
                            <h4>3. Contenu</h4>
                            <div class="content-sections">
                                <label><input type="checkbox" checked> Résumé exécutif</label>
                                <label><input type="checkbox" checked> Données détaillées</label>
                                <label><input type="checkbox"> Graphiques et charts</label>
                                <label><input type="checkbox"> Recommandations</label>
                                <label><input type="checkbox"> Plans d'action</label>
                                <label><input type="checkbox"> Annexes</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="this.closest('.modal').remove()">Annuler</button>
                    <button class="btn btn-primary" onclick="reportManager.generateReport()">Générer</button>
                </div>
            </div>
        `;
        
        // Add event listeners for step navigation
        modal.addEventListener('click', (e) => {
            if (e.target.matches('.type-option')) {
                modal.querySelectorAll('.type-option').forEach(opt => opt.classList.remove('selected'));
                e.target.classList.add('selected');
            }
        });
        
        return modal;
    }

    async generateReport() {
        // Implementation for generating custom reports
        const selectedType = document.querySelector('.type-option.selected')?.dataset.type;
        const name = document.getElementById('reportName').value;
        const description = document.getElementById('reportDescription').value;
        const period = document.getElementById('reportPeriod').value;
        const formats = Array.from(document.querySelectorAll('.format-options input:checked')).map(cb => cb.value);
        const sections = Array.from(document.querySelectorAll('.content-sections input:checked')).map(cb => cb.nextSibling.textContent.trim());
        
        if (!selectedType || !name) {
            this.showNotification('Veuillez remplir tous les champs requis', 'error');
            return;
        }
        
        try {
            const response = await fetch('api/generate_custom_report.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    type: selectedType,
                    name,
                    description,
                    period,
                    formats,
                    sections
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showNotification('Rapport généré avec succès', 'success');
                document.querySelector('.report-builder-modal').remove();
                // Refresh reports list
                this.loadReports();
            } else {
                this.showNotification(result.message || 'Erreur lors de la génération', 'error');
            }
        } catch (error) {
            console.error('Generation error:', error);
            this.showNotification('Erreur lors de la génération', 'error');
        }
    }

    async loadReports() {
        // Load reports from server
        try {
            const response = await fetch('api/get_reports.php');
            const reports = await response.json();
            this.renderReports(reports);
        } catch (error) {
            console.error('Error loading reports:', error);
        }
    }

    renderReports(reports) {
        // Implementation for rendering reports in the UI
        // This would update the reports grid with the latest data
    }

    initializeCharts() {
        // Initialize charts for reports dashboard
        this.initDownloadsChart();
        this.initTypesChart();
    }

    initDownloadsChart() {
        const ctx = document.getElementById('downloadsChart');
        if (!ctx) return;
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Téléchargements',
                    data: [45, 52, 38, 67, 73, 89],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    initTypesChart() {
        const ctx = document.getElementById('typesChart');
        if (!ctx) return;
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Risk Assessment', 'Compliance', 'Financial', 'Security', 'Executive'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        '#ef4444',
                        '#f59e0b',
                        '#10b981',
                        '#3b82f6',
                        '#8b5cf6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.reportManager = new ReportManager();
});

// Additional CSS for enhanced features
const additionalStyles = `
<style>
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    max-width: 400px;
    animation: slideIn 0.3s ease-out;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    color: white;
}

.notification-success .notification-content {
    background: linear-gradient(135deg, #10b981, #059669);
}

.notification-error .notification-content {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.notification-info .notification-content {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}

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

.modal.large .modal-content {
    max-width: 800px;
    width: 90%;
}

.builder-steps .step {
    display: none;
}

.builder-steps .step.active {
    display: block;
}

.report-types {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin: 20px 0;
}

.type-option {
    padding: 20px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.type-option:hover {
    border-color: #3b82f6;
    transform: translateY(-2px);
}

.type-option.selected {
    border-color: #3b82f6;
    background: rgba(59, 130, 246, 0.1);
}

.type-option i {
    font-size: 32px;
    color: #3b82f6;
    margin-bottom: 12px;
}

.type-option h5 {
    margin: 8px 0;
    color: #1e293b;
}

.type-option p {
    font-size: 14px;
    color: #64748b;
    margin: 0;
}

.config-options .form-group {
    margin-bottom: 20px;
}

.format-options {
    display: flex;
    gap: 16px;
}

.format-options label {
    display: flex;
    align-items: center;
    gap: 8px;
}

.content-sections {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.content-sections label {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    border-radius: 6px;
    transition: background-color 0.2s ease;
}

.content-sections label:hover {
    background-color: #f8fafc;
}

.share-modal .modal-content,
.schedule-modal .modal-content {
    max-width: 500px;
}

.share-options,
.schedule-options {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.share-option,
.schedule-option {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.share-option label,
.schedule-option label {
    font-weight: 600;
    color: #374151;
}

.link-container {
    display: flex;
    gap: 8px;
}

.link-container input {
    flex: 1;
}

.link-container button {
    padding: 8px 12px;
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    cursor: pointer;
}

.link-container button:hover {
    background: #e5e7eb;
}
</style>
`;

// Inject additional styles
document.head.insertAdjacentHTML('beforeend', additionalStyles);

