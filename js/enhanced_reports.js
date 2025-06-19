class ReportManager {
    constructor() {
        this.initDownloadButtons();
    }

    initDownloadButtons() {
        const buttons = document.querySelectorAll('.download-btn');
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleDownload(button);
            });
        });
    }

    async handleDownload(button) {
        const reportId = button.getAttribute('data-report-id');
        const format = button.getAttribute('data-format') || 'pdf';

        // Disable button and show progress
        button.disabled = true;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Downloading...';

        try {
            // Simulate download process - replace with actual download logic
            await this.downloadReport(reportId, format);

            // After download complete
            button.innerHTML = '<i class="fas fa-check"></i> Downloaded';
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        } catch (error) {
            console.error('Download failed:', error);
            button.innerHTML = originalText;
            button.disabled = false;
            alert('Download failed. Please try again.');
        }
    }

    downloadReport(reportId, format) {
        // This function should implement the actual download logic,
        // e.g., making an AJAX request to the server to get the file,
        // then triggering a download in the browser.

        // For demonstration, simulate a delay
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve();
            }, 2000);
        });
    }
}

window.ReportManager = ReportManager;
