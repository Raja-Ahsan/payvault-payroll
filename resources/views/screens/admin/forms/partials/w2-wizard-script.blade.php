<script>
(function () {
    const employees = window.__W2_WIZARD_EMPLOYEES || [];
    const taxYear = window.__W2_TAX_YEAR || new Date().getFullYear();
    const moneyKeys = ['b1','b2','b3','b4','b5','b6','b7','b8','b10','b11','b15s1','b15e1','b160','b161','b170','b171','b180','b181','b190','b191'];
    for (let i = 0; i < 4; i++) {
        moneyKeys.push('b12a' + i, 'b14n' + i);
    }

    function emptyRow() {
        const r = {
            b13stat: false,
            b13ret: false,
            b13tp: false,
        };
        moneyKeys.forEach(function (k) { r[k] = '0.00'; });
        for (let i = 0; i < 4; i++) {
            r['b12c' + i] = '';
            r['b14t' + i] = '';
        }
        r.b15s0 = '';
        r.b15e0 = '';
        r.b200 = '';
        r.b201 = '';
        return r;
    }

    const byEmp = {};
    employees.forEach(function (e) {
        byEmp[e.id] = emptyRow();
    });

    let currentStep = 0;
    let currentEmpId = employees[0] ? employees[0].id : null;
    let editingEnabled = false;
    let w3AttentionAck = false;
    let paperModalCallback = null;

    const panels = [
        document.getElementById('wizard-step-0'),
        document.getElementById('wizard-step-1'),
        document.getElementById('wizard-step-2'),
    ];
    const wizBack = document.getElementById('wizBack');
    const wizNext = document.getElementById('wizNext');
    const sel = document.getElementById('w2EmployeeSelect');
    const btnJumpW3 = document.getElementById('btnJumpW3');
    const btnW2Preview = document.getElementById('btnW2Preview');
    const btnW2Print = document.getElementById('btnW2Print');
    const btnOverrideToggle = document.getElementById('btnOverrideToggle');
    const w3Actions = document.getElementById('w3Actions');
    const btnW3Preview = document.getElementById('btnW3Preview');
    const btnW3Print = document.getElementById('btnW3Print');

    const overrideModalEl = document.getElementById('w2OverrideModal');
    const paperModalEl = document.getElementById('paperFilingModal');
    const w2PreviewModalEl = document.getElementById('w2PreviewModal');
    const w3PreviewModalEl = document.getElementById('w3PreviewModal');
    const w2PreviewMount = document.getElementById('w2PreviewMount');
    const w3PreviewMount = document.getElementById('w3PreviewMount');
    const w2PreviewPrint = document.getElementById('w2PreviewPrint');
    const w3PreviewPrint = document.getElementById('w3PreviewPrint');
    const w2OverrideAck = document.getElementById('w2OverrideAck');
    const w2OverrideOk = document.getElementById('w2OverrideOk');
    const paperFilingUnderstand = document.getElementById('paperFilingUnderstand');

    if (!panels[0] || typeof bootstrap === 'undefined') {
        return;
    }

    const bsOverride = bootstrap.Modal.getOrCreateInstance(overrideModalEl);
    const bsPaper = bootstrap.Modal.getOrCreateInstance(paperModalEl);
    const bsW2Preview = w2PreviewModalEl ? bootstrap.Modal.getOrCreateInstance(w2PreviewModalEl) : null;
    const bsW3Preview = w3PreviewModalEl ? bootstrap.Modal.getOrCreateInstance(w3PreviewModalEl) : null;

    employees.forEach(function (e) {
        const opt = document.createElement('option');
        opt.value = String(e.id);
        opt.textContent = e.label;
        sel.appendChild(opt);
    });
    if (employees[0]) {
        sel.value = String(employees[0].id);
    }

    function parseMoney(s) {
        var n = parseFloat(String(s || '').replace(/,/g, ''));
        return isNaN(n) ? 0 : n;
    }

    function formatMoney(n) {
        return (Math.round(n * 100) / 100).toFixed(2);
    }

    function sanitizeMoneyInput(raw) {
        var s = String(raw || '').replace(/[^\d.]/g, '');
        var dot = s.indexOf('.');
        if (dot !== -1) {
            s = s.slice(0, dot + 1) + s.slice(dot + 1).replace(/\./g, '');
        }
        if (s.length > 9) {
            s = s.slice(0, 9);
        }
        return s;
    }

    function sanitizeState(raw) {
        return String(raw || '').toUpperCase().replace(/[^A-Z]/g, '').slice(0, 2);
    }

    function sanitizeCode(raw) {
        return String(raw || '').replace(/[^a-zA-Z0-9]/g, '');
    }

    function sanitizeAlphaNum(raw) {
        return String(raw || '').replace(/[^a-zA-Z0-9\s\-]/g, '');
    }

    function persistForm() {
        if (currentEmpId === null) {
            return;
        }
        var row = byEmp[currentEmpId];
        document.querySelectorAll('.w2-field').forEach(function (el) {
            var key = el.getAttribute('data-w2-field');
            if (!key) {
                return;
            }
            if (el.type === 'checkbox') {
                row[key] = el.checked;
            } else {
                row[key] = el.value;
            }
        });
    }

    function applyReadonlyToFields(locked) {
        document.querySelectorAll('.w2-field').forEach(function (el) {
            if (el.type === 'checkbox') {
                el.disabled = locked;
            } else {
                el.readOnly = locked;
                el.classList.toggle('bg-light', locked);
            }
        });
    }

    function loadForm() {
        var row = byEmp[currentEmpId];
        if (!row) {
            return;
        }
        document.querySelectorAll('.w2-field').forEach(function (el) {
            var key = el.getAttribute('data-w2-field');
            if (!key) {
                return;
            }
            if (el.type === 'checkbox') {
                el.checked = !!row[key];
            } else {
                el.value = row[key] == null ? '' : row[key];
            }
        });
        applyReadonlyToFields(!editingEnabled);
    }

    function setEditing(on) {
        editingEnabled = on;
        btnOverrideToggle.textContent = on ? 'Enable calculations again' : 'Override calculations';
        applyReadonlyToFields(!on);
    }

    function bindFieldInput(el) {
        var t = el.getAttribute('data-field-type');
        if (!t || el.type === 'checkbox') {
            return;
        }
        el.addEventListener('input', function () {
            if (!editingEnabled) {
                return;
            }
            if (t === 'money') {
                el.value = sanitizeMoneyInput(el.value);
            } else if (t === 'state') {
                el.value = sanitizeState(el.value);
            } else if (t === 'code') {
                el.value = sanitizeCode(el.value);
            } else if (t === 'alpha') {
                el.value = sanitizeAlphaNum(el.value);
            }
        });
        el.addEventListener('blur', function () {
            if (!editingEnabled || t !== 'money') {
                return;
            }
            var n = parseMoney(el.value);
            el.value = formatMoney(n);
        });
    }

    document.querySelectorAll('.w2-field').forEach(bindFieldInput);

    sel.addEventListener('change', function () {
        persistForm();
        currentEmpId = parseInt(sel.value, 10);
        if (isNaN(currentEmpId)) {
            currentEmpId = sel.value;
        }
        loadForm();
    });

    btnOverrideToggle.addEventListener('click', function () {
        if (editingEnabled) {
            setEditing(false);
            return;
        }
        w2OverrideAck.checked = false;
        w2OverrideOk.disabled = true;
        bsOverride.show();
    });

    w2OverrideAck.addEventListener('change', function () {
        w2OverrideOk.disabled = !w2OverrideAck.checked;
    });

    w2OverrideOk.addEventListener('click', function () {
        bsOverride.hide();
        setEditing(true);
    });

    function showPaperModal(onDone) {
        paperModalCallback = typeof onDone === 'function' ? onDone : null;
        bsPaper.show();
    }

    paperFilingUnderstand.addEventListener('click', function () {
        bsPaper.hide();
        if (paperModalCallback) {
            var cb = paperModalCallback;
            paperModalCallback = null;
            cb();
        }
    });

    function getEmployeeById(empId) {
        return employees.find(function (x) { return String(x.id) === String(empId); }) || null;
    }

    function getCompanyForEmp(empId) {
        var e = getEmployeeById(empId);
        return (e && e.company) ? e.company : (employees[0] && employees[0].company) || {};
    }

    function aggregateTotals() {
        var totals = emptyRow();
        employees.forEach(function (emp) {
            var r = byEmp[emp.id];
            moneyKeys.forEach(function (k) {
                if (k.indexOf('b12a') === 0 || k.indexOf('b14n') === 0) {
                    return;
                }
                totals[k] = formatMoney(parseMoney(totals[k]) + parseMoney(r[k]));
            });
        });
        var sum12 = 0;
        var sum14n = 0;
        employees.forEach(function (emp) {
            var r = byEmp[emp.id];
            var i;
            for (i = 0; i < 4; i++) {
                sum12 += parseMoney(r['b12a' + i]);
                sum14n += parseMoney(r['b14n' + i]);
            }
        });
        totals.b12c0 = '';
        totals.b12a0 = formatMoney(sum12);
        for (var j = 1; j < 4; j++) {
            totals['b12c' + j] = '';
            totals['b12a' + j] = '0.00';
        }
        totals.b14t0 = 'Combined other (sum)';
        totals.b14n0 = formatMoney(sum14n);
        for (var k = 1; k < 4; k++) {
            totals['b14t' + k] = '';
            totals['b14n' + k] = '0.00';
        }
        var first = byEmp[employees[0] && employees[0].id];
        if (first) {
            totals.b15s0 = first.b15s0;
            totals.b15e0 = first.b15e0;
            totals.b200 = first.b200;
            totals.b201 = first.b201;
        }
        return totals;
    }

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function watermarkHtml() {
        return '<div style="position:absolute;left:0;right:0;top:18%;text-align:center;font-size:2rem;font-weight:bold;color:#000;opacity:0.35;pointer-events:none;transform:rotate(-12deg);z-index:0;">Not Updated — Do NOT File</div>';
    }

    function printCssW2() {
        return ''
            + '<style>'
            + '*{box-sizing:border-box}'
            + '.w2-print-root{font-family:system-ui,sans-serif;padding:12px;background:#e9e9e9;min-height:100%}'
            + '.w2-doc{position:relative;z-index:1;background:#fff;padding:16px;max-width:900px;margin:0 auto;box-shadow:0 1px 4px rgba(0,0,0,.12)}'
            + '.w2-doc h2{font-size:1rem;margin:0 0 8px;border-bottom:1px solid #222;padding-bottom:4px}'
            + '.w2-doc table{width:100%;border-collapse:collapse;font-size:0.8rem;margin-top:8px}'
            + '.w2-doc th,.w2-doc td{border:1px solid #999;padding:6px 8px;text-align:left;vertical-align:top}'
            + '.w2-doc th{background:#f3f3f3;width:38%}'
            + '.w2-doc .num{text-align:right;font-variant-numeric:tabular-nums}'
            + '@media print{.w2-print-root{background:#fff;padding:0}.w2-doc{box-shadow:none}}'
            + '</style>';
    }

    function printCssW3() {
        return ''
            + '<style>'
            + '*{box-sizing:border-box}'
            + '.w3-print-root{font-family:system-ui,sans-serif;padding:12px;background:#e9e9e9;min-height:100%}'
            + '.w3-doc{position:relative;z-index:1;background:#fff;padding:16px;max-width:900px;margin:0 auto;box-shadow:0 1px 4px rgba(0,0,0,.12)}'
            + '.w3-doc h2{font-size:1rem;margin:0 0 8px;border-bottom:1px solid #222;padding-bottom:4px}'
            + '.w3-doc table{width:100%;border-collapse:collapse;font-size:0.8rem;margin-top:8px}'
            + '.w3-doc th,.w3-doc td{border:1px solid #999;padding:6px 8px;text-align:left;vertical-align:top}'
            + '.w3-doc th{background:#f3f3f3;width:38%}'
            + '.w3-doc .num{text-align:right;font-variant-numeric:tabular-nums}'
            + '@media print{.w3-print-root{background:#fff;padding:0}.w3-doc{box-shadow:none}}'
            + '</style>';
    }

    function buildW2PreviewBody() {
        persistForm();
        var c = getCompanyForEmp(currentEmpId);
        var r = byEmp[currentEmpId];
        var e = getEmployeeById(currentEmpId) || {};
        var name = escapeHtml([e.first_name, e.last_name].filter(Boolean).join(' ').trim());
        var b12 = '';
        var i;
        for (i = 0; i < 4; i++) {
            b12 += '<tr><th>12d Code (' + (i + 1) + ')</th><td>' + escapeHtml(r['b12c' + i]) + '</td><th>12d Amount (' + (i + 1) + ')</th><td class="num">' + escapeHtml(r['b12a' + i]) + '</td></tr>';
        }
        var b14 = '';
        for (i = 0; i < 4; i++) {
            b14 += '<tr><th>14 Description (' + (i + 1) + ')</th><td>' + escapeHtml(r['b14t' + i]) + '</td><th>14 Amount (' + (i + 1) + ')</th><td class="num">' + escapeHtml(r['b14n' + i]) + '</td></tr>';
        }
        return ''
            + '<div class="w2-doc">'
            + '<h2>Form W-2 Wage and Tax Statement — ' + escapeHtml(String(taxYear)) + '</h2>'
            + '<table>'
            + '<tr><th>Employer name, address, ZIP</th><td colspan="3">' + escapeHtml(c.name || '') + '<br>' + escapeHtml(c.line1 || '') + '<br>' + escapeHtml(c.cityStateZip || '') + '</td></tr>'
            + '<tr><th>Employer identification number (EIN)</th><td colspan="3">' + escapeHtml(c.ein || '') + '</td></tr>'
            + '<tr><th>Employee&apos;s name</th><td colspan="3">' + name + '</td></tr>'
            + '<tr><th>Employee&apos;s social security number</th><td colspan="3">' + escapeHtml(e.ssn || '') + '</td></tr>'
            + '<tr><th>1 Wages, tips, other compensation</th><td class="num">' + escapeHtml(r.b1) + '</td><th>2 Federal income tax withheld</th><td class="num">' + escapeHtml(r.b2) + '</td></tr>'
            + '<tr><th>3 Social security wages</th><td class="num">' + escapeHtml(r.b3) + '</td><th>4 Social security tax withheld</th><td class="num">' + escapeHtml(r.b4) + '</td></tr>'
            + '<tr><th>5 Medicare wages and tips</th><td class="num">' + escapeHtml(r.b5) + '</td><th>6 Medicare tax withheld</th><td class="num">' + escapeHtml(r.b6) + '</td></tr>'
            + '<tr><th>7 Social security tips</th><td class="num">' + escapeHtml(r.b7) + '</td><th>8 Allocated tips</th><td class="num">' + escapeHtml(r.b8) + '</td></tr>'
            + '<tr><th>10 Dependent care benefits</th><td class="num">' + escapeHtml(r.b10) + '</td><th>11 Nonqualified plans</th><td class="num">' + escapeHtml(r.b11) + '</td></tr>'
            + b12
            + '<tr><th>13 Statutory employee</th><td>' + (r.b13stat ? 'Yes' : '') + '</td><th>13 Retirement plan</th><td>' + (r.b13ret ? 'Yes' : '') + '</td></tr>'
            + '<tr><th>13 Third-party sick pay</th><td colspan="3">' + (r.b13tp ? 'Yes' : '') + '</td></tr>'
            + b14
            + '<tr><th>15 State / Employer state ID</th><td>' + escapeHtml(r.b15s0) + ' / ' + escapeHtml(r.b15e0) + '</td><th>15 amounts</th><td class="num">' + escapeHtml(r.b15s1) + ' / ' + escapeHtml(r.b15e1) + '</td></tr>'
            + '<tr><th>16 State wages</th><td class="num">' + escapeHtml(r.b160) + ' / ' + escapeHtml(r.b161) + '</td><th>17 State income tax</th><td class="num">' + escapeHtml(r.b170) + ' / ' + escapeHtml(r.b171) + '</td></tr>'
            + '<tr><th>18 Local wages</th><td class="num">' + escapeHtml(r.b180) + ' / ' + escapeHtml(r.b181) + '</td><th>19 Local income tax</th><td class="num">' + escapeHtml(r.b190) + ' / ' + escapeHtml(r.b191) + '</td></tr>'
            + '<tr><th>20 Locality name</th><td colspan="3">' + escapeHtml(r.b200) + ' / ' + escapeHtml(r.b201) + '</td></tr>'
            + '</table></div>';
    }

    function buildW3PreviewBody(t) {
        var c = getCompanyForEmp(currentEmpId);
        var n = employees.length;
        return ''
            + '<div class="w3-doc">'
            + '<h2>Form W-3 Transmittal of Wage and Tax Statements — ' + escapeHtml(String(taxYear)) + '</h2>'
            + '<table>'
            + '<tr><th>c Total number of Forms W-2</th><td colspan="3" class="num">' + n + '</td></tr>'
            + '<tr><th>e Employer identification number (EIN)</th><td colspan="3">' + escapeHtml(c.ein || '') + '</td></tr>'
            + '<tr><th>f Employer name</th><td colspan="3">' + escapeHtml(c.name || '') + '</td></tr>'
            + '<tr><th>f Employer address</th><td colspan="3">' + escapeHtml([c.line1, c.cityStateZip].filter(Boolean).join(', ')) + '</td></tr>'
            + '<tr><th>1 Wages, tips, other compensation</th><td colspan="3" class="num">' + escapeHtml(t.b1) + '</td></tr>'
            + '<tr><th>2 Federal income tax withheld</th><td colspan="3" class="num">' + escapeHtml(t.b2) + '</td></tr>'
            + '<tr><th>3 Social security wages</th><td colspan="3" class="num">' + escapeHtml(t.b3) + '</td></tr>'
            + '<tr><th>4 Social security tax withheld</th><td colspan="3" class="num">' + escapeHtml(t.b4) + '</td></tr>'
            + '<tr><th>5 Medicare wages and tips</th><td colspan="3" class="num">' + escapeHtml(t.b5) + '</td></tr>'
            + '<tr><th>6 Medicare tax withheld</th><td colspan="3" class="num">' + escapeHtml(t.b6) + '</td></tr>'
            + '<tr><th>7 Social security tips</th><td colspan="3" class="num">' + escapeHtml(t.b7) + '</td></tr>'
            + '<tr><th>8 Allocated tips</th><td colspan="3" class="num">' + escapeHtml(t.b8) + '</td></tr>'
            + '<tr><th>10 Dependent care benefits</th><td colspan="3" class="num">' + escapeHtml(t.b10) + '</td></tr>'
            + '<tr><th>11 Nonqualified plans</th><td colspan="3" class="num">' + escapeHtml(t.b11) + '</td></tr>'
            + '<tr><th>12a Amount (combined)</th><td colspan="3" class="num">' + escapeHtml(t.b12a0) + '</td></tr>'
            + '<tr><th>15 State / Employer state ID</th><td colspan="3">' + escapeHtml((t.b15s0 || '') + ' / ' + (t.b15e0 || '')) + '</td></tr>'
            + '<tr><th>16 State wages, tips, etc.</th><td colspan="3" class="num">' + escapeHtml(t.b160) + ' / ' + escapeHtml(t.b161) + '</td></tr>'
            + '<tr><th>17 State income tax</th><td colspan="3" class="num">' + escapeHtml(t.b170) + ' / ' + escapeHtml(t.b171) + '</td></tr>'
            + '<tr><th>18 Local wages, tips, etc.</th><td colspan="3" class="num">' + escapeHtml(t.b180) + ' / ' + escapeHtml(t.b181) + '</td></tr>'
            + '<tr><th>19 Local income tax</th><td colspan="3" class="num">' + escapeHtml(t.b190) + ' / ' + escapeHtml(t.b191) + '</td></tr>'
            + '<tr><th>20 Locality name</th><td colspan="3">' + escapeHtml(t.b200) + ' / ' + escapeHtml(t.b201) + '</td></tr>'
            + '<tr><th>Contact</th><td colspan="3">' + escapeHtml(c.contact || '') + '</td></tr>'
            + '<tr><th>Phone</th><td colspan="3">' + escapeHtml(c.phone || '') + '</td></tr>'
            + '<tr><th>Email</th><td colspan="3">' + escapeHtml(c.email || '') + '</td></tr>'
            + '</table></div>';
    }

    function wrapPrintHtml(title, innerBody) {
        return '<!DOCTYPE html><html><head><meta charset="utf-8"><title>' + escapeHtml(title) + '</title>'
            + printCssW2().replace(/w2-print-root/g, 'w2-print-root').replace(/w2-doc/g, 'w2-doc')
            + '</head><body><div class="w2-print-root">' + innerBody + '</div></body></html>';
    }

    function openPrintWindow(html) {
        var w = window.open('', '_blank');
        if (!w) {
            return;
        }
        w.document.open();
        w.document.write(html);
        w.document.close();
        w.focus();
        setTimeout(function () {
            w.print();
        }, 250);
    }

    function collectBoxesForCurrentEmployee() {
        persistForm();
        var r = byEmp[currentEmpId];
        if (!r) {
            return {};
        }
        var o = {};
        Object.keys(r).forEach(function (k) {
            o[k] = r[k];
        });
        return o;
    }

    function downloadPdf(url, body, filenameHint) {
        var meta = document.querySelector('meta[name="csrf-token"]');
        if (!meta) {
            alert('Missing CSRF token. Refresh the page and try again.');
            return;
        }
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/pdf',
                'X-CSRF-TOKEN': meta.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(body)
        }).then(function (res) {
            if (!res.ok) {
                throw new Error('bad status');
            }
            return res.blob();
        }).then(function (blob) {
            var u = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = u;
            a.download = filenameHint || 'form.pdf';
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(u);
        }).catch(function () {
            alert('Could not generate the PDF. Please try again.');
        });
    }

    if (btnW2Preview && w2PreviewMount && bsW2Preview) {
        btnW2Preview.addEventListener('click', function () {
            w2PreviewMount.innerHTML = printCssW2() + '<div class="w2-print-root" style="background:#e9e9e9;padding:12px;min-height:200px;position:relative;">'
                + watermarkHtml()
                + buildW2PreviewBody()
                + '</div>';
            bsW2Preview.show();
        });
    }

    if (w2PreviewPrint) {
        w2PreviewPrint.addEventListener('click', function () {
            var inner = w2PreviewMount ? w2PreviewMount.querySelector('.w2-doc') : null;
            var body = inner ? inner.outerHTML : buildW2PreviewBody();
            openPrintWindow(wrapPrintHtml('Form W-2 — ' + String(taxYear), '<div style="position:relative;">' + watermarkHtml() + body + '</div>'));
        });
    }

    if (btnW2Print && window.__W2_PDF_URL) {
        btnW2Print.addEventListener('click', function () {
            persistForm();
            var e = getEmployeeById(currentEmpId);
            var c = getCompanyForEmp(currentEmpId);
            var payload = {
                snapshot: {
                    taxYear: taxYear,
                    employeeId: currentEmpId,
                    employee: {
                        first_name: (e && e.first_name) || '',
                        last_name: (e && e.last_name) || '',
                        ssn: (e && e.ssn) || '',
                    },
                    company: c,
                    boxes: collectBoxesForCurrentEmployee(),
                }
            };
            btnW2Print.disabled = true;
            downloadPdf(window.__W2_PDF_URL, payload, 'Form-W-2-' + String(taxYear) + '-' + String(currentEmpId) + '.pdf').finally(function () {
                btnW2Print.disabled = false;
            });
        });
    }

    if (btnW3Preview && w3PreviewMount && bsW3Preview) {
        btnW3Preview.addEventListener('click', function () {
            persistForm();
            var t = aggregateTotals();
            w3PreviewMount.innerHTML = printCssW3()
                + '<div class="w3-print-root" style="background:#e9e9e9;padding:12px;min-height:200px;position:relative;">'
                + watermarkHtml()
                + buildW3PreviewBody(t)
                + '</div>';
            bsW3Preview.show();
        });
    }

    if (w3PreviewPrint) {
        w3PreviewPrint.addEventListener('click', function () {
            var inner = w3PreviewMount ? w3PreviewMount.querySelector('.w3-doc') : null;
            var body = inner ? inner.outerHTML : '';
            var css = printCssW3();
            var html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Form W-3 — ' + escapeHtml(String(taxYear)) + '</title>'
                + css + '</head><body><div class="w3-print-root" style="position:relative;">' + watermarkHtml() + body + '</div></body></html>';
            openPrintWindow(html);
        });
    }

    if (btnW3Print && window.__W3_PDF_URL) {
        btnW3Print.addEventListener('click', function () {
            persistForm();
            var t = aggregateTotals();
            var c = getCompanyForEmp(currentEmpId);
            var payload = {
                snapshot: {
                    taxYear: taxYear,
                    employeeCount: employees.length,
                    company: c,
                    totals: t,
                }
            };
            btnW3Print.disabled = true;
            downloadPdf(window.__W3_PDF_URL, payload, 'Form-W-3-' + String(taxYear) + '.pdf').finally(function () {
                btnW3Print.disabled = false;
            });
        });
    }

    window.printW3FromPreview = function () {
        if (w3PreviewPrint) {
            w3PreviewPrint.click();
        }
    };

    function syncW3Ui() {
        if (!w3Actions || !wizNext) {
            return;
        }
        if (currentStep === 2) {
            if (!w3AttentionAck) {
                wizNext.disabled = true;
                wizNext.textContent = 'Next >';
                w3Actions.classList.add('opacity-50');
                w3Actions.style.pointerEvents = 'none';
            } else {
                wizNext.disabled = false;
                wizNext.textContent = 'Finish';
                w3Actions.classList.remove('opacity-50');
                w3Actions.style.pointerEvents = '';
            }
        } else {
            wizNext.textContent = 'Next >';
        }
    }

    function onEnterStep2() {
        syncW3Ui();
        if (!w3AttentionAck) {
            paperModalCallback = function () {
                w3AttentionAck = true;
                syncW3Ui();
            };
            bsPaper.show();
        }
    }

    function showStep(i) {
        currentStep = i;
        panels.forEach(function (el, idx) {
            if (!el) {
                return;
            }
            el.classList.toggle('d-none', idx !== i);
        });
        wizBack.disabled = i === 0;
        if (i === 2) {
            onEnterStep2();
        } else {
            wizNext.disabled = false;
            syncW3Ui();
        }
    }

    wizNext.addEventListener('click', function () {
        if (currentStep === 2 && w3AttentionAck) {
            window.location.href = @json(route('admin.forms.index'));
            return;
        }
        if (currentStep < panels.length - 1) {
            if (currentStep === 1) {
                persistForm();
            }
            showStep(currentStep + 1);
        }
    });

    wizBack.addEventListener('click', function () {
        if (currentStep > 0) {
            if (currentStep === 2) {
                persistForm();
            }
            showStep(currentStep - 1);
        }
    });

    btnJumpW3.addEventListener('click', function () {
        persistForm();
        showStep(2);
    });

    showStep(0);
    if (currentEmpId !== null) {
        loadForm();
    }

    overrideModalEl.addEventListener('hidden.bs.modal', function () {
        w2OverrideAck.checked = false;
        w2OverrideOk.disabled = true;
    });
})();
</script>
