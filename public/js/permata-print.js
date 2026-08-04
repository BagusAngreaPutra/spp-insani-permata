'use strict';

/* Print document system */
document.addEventListener('DOMContentLoaded', () => {
        const receiptRoots = document.querySelectorAll(
            '.receipt-paper, .print-kwitansi, .pi-receipt-document, [data-receipt-document]'
        );

        receiptRoots.forEach((root) => {
            root.classList.add('pi-print-document', 'pi-receipt-document');
            root.querySelectorAll('table').forEach((table) => {
                table.dataset.sortDisabled = 'true';
                table.dataset.sortable = 'false';

                const sortbar = table.previousElementSibling;
                if (sortbar?.classList.contains('pi-table-sortbar')) sortbar.remove();

                table.querySelectorAll('.pi-sort-indicator, .pi-sort-sr').forEach((item) => item.remove());
                table.querySelectorAll('thead th').forEach((header) => {
                    header.classList.remove('pi-sortable-column');
                    header.removeAttribute('tabindex');
                    header.removeAttribute('aria-sort');
                    header.removeAttribute('title');
                    header.querySelectorAll(':scope > i').forEach((icon) => icon.remove());
                });
            });
        });
    });
