<script>
(function () {
    const employees = window.__W2_WIZARD_EMPLOYEES || [];
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
    const w3PreviewModalEl = document.getElementById('w3PreviewModal');
    const w3PreviewMount = document.getElementById('w3PreviewMount');
    const w2OverrideAck = document.getElementById('w2OverrideAck');
    const w2OverrideOk = document.getElementById('w2OverrideOk');
    const paperFilingUnderstand = document.getElementById('paperFilingUnderstand');

    if (!panels[0] || typeof bootstrap === 'undefined') {
        return;
    }

    const bsOverride = bootstrap.Modal.getOrCreateInstance(overrideModalEl);
    const bsPaper = bootstrap.Modal.getOrCreateInstance(paperModalEl);
    const bsW3Preview = bootstrap.Modal.getOrCreateInstance(w3PreviewModalEl);

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
    // pehla modal band
    bsPaper.hide();

    // calculations generate
    var totals = aggregateTotals();

    // preview me inject
    w3PreviewMount.innerHTML = w3GridHtml(totals);

    // second modal open
    bsW3Preview.show();
});

    function getCompanyForEmp(empId) {
        var e = employees.find(function (x) { return String(x.id) === String(empId); });
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

    function watermarkCss() {
        return 'position:relative;min-height:420px;';
    }

    function watermarkHtml() {
        return '<div style="position:absolute;left:0;right:0;top:18%;text-align:center;font-size:2rem;font-weight:bold;color:#b00020;opacity:0.35;pointer-events:none;transform:rotate(-12deg);">Not Updated — Do NOT File</div>';
    }

    function buildW2PrintDocument() {
        persistForm();
        var c = getCompanyForEmp(currentEmpId);
        var r = byEmp[currentEmpId];
        var rows = moneyKeys.concat([]).filter(function (k) { return k.indexOf('b12c') !== 0; });
        var nums = ['b1','b2','b3','b4','b5','b6','b7','b8','b10','b11','b160','b161','b170','b171','b180','b181','b190','b191'];
        var lineNums = nums.map(function (k) { return r[k] || '0.00'; }).join('  ');
        var body = ''
            + '<pre style="font-family:ui-monospace,Menlo,monospace;font-size:11px;line-height:1.35;margin:0;padding:24px;' + watermarkCss() + '">'
            + watermarkHtml()
            + '<div style="position:relative;z-index:1;">'
            + '1\n'
            + escapeHtml(c.ein || '') + '\n'
            + escapeHtml(c.name || '') + '\n'
            + escapeHtml(c.line1 || '') + '\n'
            + escapeHtml(c.cityStateZip || '') + '\n\n'
            + escapeHtml(lineNums) + '\n\n'
            + escapeHtml((r.b15s0 || '') + ' ' + (r.b15e0 || '')) + '\n\n'
            + escapeHtml([r.b160, r.b170, r.b180, r.b190].join('  ')) + '\n'
            + escapeHtml(c.contact || '') + '  ' + escapeHtml(c.phone || '') + '\n'
            + escapeHtml(c.email || '') + '\n'
            + '</div></pre>';
        return wrapPrintHtml('W-2', body);
    }

    function w3GridHtml(t) {
        var c = getCompanyForEmp(currentEmpId);
        var n = employees.length;
        return ''
            + '<div class="p-3" style="' + watermarkCss() + '">'
            + watermarkHtml()
            + '<div style="position:relative;z-index:1;">'
            + '<div class="fw-bold mb-2">Form W-3 — Transmittal (preview)</div>'
            + '<table class="table table-bordered table-sm w2-form-table" style="max-width:900px;">'
            + '<tr><td>c Total number of Forms W-2</td><td>' + n + '</td></tr>'
            + '<tr><td>e Employer identification number (EIN)</td><td>' + escapeHtml(c.ein || '') + '</td></tr>'
            + '<tr><td>f Employer name</td><td>' + escapeHtml(c.name || '') + '</td></tr>'
            + '<tr><td>f Address</td><td>' + escapeHtml([c.line1, c.cityStateZip].filter(Boolean).join(', ')) + '</td></tr>'
            + '<tr><td>1 Wages, tips, other compensation</td><td>' + escapeHtml(t.b1) + '</td></tr>'
            + '<tr><td>2 Federal income tax withheld</td><td>' + escapeHtml(t.b2) + '</td></tr>'
            + '<tr><td>3 Social security wages</td><td>' + escapeHtml(t.b3) + '</td></tr>'
            + '<tr><td>4 Social security tax withheld</td><td>' + escapeHtml(t.b4) + '</td></tr>'
            + '<tr><td>5 Medicare wages and tips</td><td>' + escapeHtml(t.b5) + '</td></tr>'
            + '<tr><td>6 Medicare tax withheld</td><td>' + escapeHtml(t.b6) + '</td></tr>'
            + '<tr><td>7 Social security tips</td><td>' + escapeHtml(t.b7) + '</td></tr>'
            + '<tr><td>8 Allocated tips</td><td>' + escapeHtml(t.b8) + '</td></tr>'
            + '<tr><td>10 Dependent care benefits</td><td>' + escapeHtml(t.b10) + '</td></tr>'
            + '<tr><td>11 Nonqualified plans</td><td>' + escapeHtml(t.b11) + '</td></tr>'
            + '<tr><td>12a Deferred compensation (sum)</td><td>' + escapeHtml(t.b12a0) + '</td></tr>'
            + '<tr><td>15 State / Employer state ID</td><td>' + escapeHtml((t.b15s0 || '') + ' / ' + (t.b15e0 || '')) + '</td></tr>'
            + '<tr><td>16 State wages, tips, etc.</td><td>' + escapeHtml(t.b160) + '</td></tr>'
            + '<tr><td>17 State income tax</td><td>' + escapeHtml(t.b170) + '</td></tr>'
            + '<tr><td>18 Local wages, tips, etc.</td><td>' + escapeHtml(t.b180) + '</td></tr>'
            + '<tr><td>19 Local income tax</td><td>' + escapeHtml(t.b190) + '</td></tr>'
            + '<tr><td>Contact</td><td>' + escapeHtml(c.contact || '') + '</td></tr>'
            + '<tr><td>Phone</td><td>' + escapeHtml(c.phone || '') + '</td></tr>'
            + '<tr><td>Email</td><td>' + escapeHtml(c.email || '') + '</td></tr>'
            + '</table></div></div>';
    }

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function wrapPrintHtml(title, innerBody) {
        return '<!DOCTYPE html><html><head><meta charset="utf-8"><title>' + escapeHtml(title) + '</title>'
            + '<style>body{margin:0;background:#fff;color:#111} @media print{ body{ print-color-adjust:exact;-webkit-print-color-adjust:exact;}}</style></head><body>'
            + innerBody + '</body></html>';
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

    btnW2Print.addEventListener('click', function () {
        openPrintWindow(buildW2PrintDocument());
    });

    btnW2Preview.addEventListener('click', function () {
        persistForm();
        showPaperModal(function () {
            var t = aggregateTotals();
            w3PreviewMount.innerHTML = w3GridHtml(t);
            bsW3Preview.show();
        });
    });

    btnW3Preview.addEventListener('click', function () {
        persistForm();
        showPaperModal(function () {
            var t = aggregateTotals();
            w3PreviewMount.innerHTML = w3GridHtml(t);
            bsW3Preview.show();
        });
    });

    btnW3Print.addEventListener('click', function () {
        persistForm();
        var t = aggregateTotals();
        openPrintWindow(wrapPrintHtml('Form W-3', w3GridHtml(t)));
    });

    window.printW3FromPreview = function () {
        var html = wrapPrintHtml('Form W-3', w3PreviewMount ? w3PreviewMount.innerHTML : '');
        openPrintWindow(html);
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
