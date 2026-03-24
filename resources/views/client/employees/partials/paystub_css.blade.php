<style type="text/css">
    .paystub-wrap {
        max-width: 900px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
        color: #1e293b;
        font-size: 14px;
        line-height: 1.5;
    }
    .paystub-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 3px solid #1D5C24;
        padding-bottom: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .paystub-brand {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.02em;
    }
    .paystub-sub {
        margin: 0.25rem 0 0;
        font-size: 0.875rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .paystub-meta {
        text-align: right;
        font-size: 0.8125rem;
    }
    .paystub-meta .lbl {
        display: block;
        color: #94a3b8;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .paystub-meta .val {
        display: block;
        font-weight: 600;
        color: #0f172a;
    }
    .paystub-meta > div { margin-bottom: 0.5rem; }
    .paystub-employee-box {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
    }
    .pe-col .lbl {
        display: block;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
    }
    .pe-col .val { font-weight: 500; }
    .pe-col .val.strong { font-size: 1.125rem; font-weight: 700; color: #0f172a; }
    .pe-wide { grid-column: 1 / -1; }
    .paystub-columns { border-collapse: separate; border-spacing: 0; }
    .paystub-col-gap { width: 16px !important; }
    .paystub-col-cell { padding: 0; }
    @media (max-width: 768px) {
        .paystub-columns,
        .paystub-columns tbody,
        .paystub-columns tr,
        .paystub-col-cell { display: block !important; width: 100% !important; }
        .paystub-col-gap { display: none !important; }
        .paystub-header { flex-direction: column; gap: 1rem; }
        .paystub-meta { text-align: left; }
    }
    .paystub-panel {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        overflow: hidden;
    }
    .panel-title {
        margin: 0;
        padding: 0.65rem 1rem;
        background: linear-gradient(135deg, #1D5C24 0%, #348C31 100%);
        color: #fff;
        font-size: 0.8125rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .paystub-table {
        width: 100%;
        border-collapse: collapse;
    }
    .paystub-table th,
    .paystub-table td {
        padding: 0.5rem 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .paystub-table th {
        text-align: left;
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #64748b;
        background: #fafafa;
    }
    .paystub-table .num { text-align: right; font-variant-numeric: tabular-nums; }
    .gross-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.85rem 1rem;
        background: #ecfdf5;
        border-top: 2px solid #34d399;
        font-weight: 700;
        font-size: 1rem;
    }
    .gross-amt { font-size: 1.25rem; color: #047857; font-variant-numeric: tabular-nums; }
    .subtotal-line {
        display: flex;
        justify-content: space-between;
        padding: 0.65rem 1rem;
        background: #fffbeb;
        border-top: 1px solid #fcd34d;
        font-weight: 600;
        font-variant-numeric: tabular-nums;
    }
    .net-pay-box {
        margin-top: 1.5rem;
        border: 2px solid #1D5C24;
        border-radius: 8px;
        background: linear-gradient(180deg, #f0fdf4 0%, #fff 100%);
    }
    .net-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
    }
    .net-label {
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #166534;
    }
    .net-amount {
        font-size: 2rem;
        font-weight: 800;
        color: #14532d;
        font-variant-numeric: tabular-nums;
    }
    .net-note {
        margin: 0;
        padding: 0 1.5rem 1rem;
        font-size: 0.75rem;
        color: #64748b;
    }
    .paystub-footer {
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px dashed #cbd5e1;
        font-size: 0.7rem;
        color: #94a3b8;
        text-align: center;
    }
    @media print {
        .no-print { display: none !important; }
        aside, body > div.flex > div.flex-1 > header { display: none !important; }
        body { background: #fff !important; }
        main { padding: 0.5rem !important; overflow: visible !important; }
        .paystub-wrap {
            box-shadow: none !important;
            border: none !important;
            max-width: 100% !important;
        }
    }
</style>
