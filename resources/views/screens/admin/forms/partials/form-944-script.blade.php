<script>
(function () {
    if (typeof bootstrap === 'undefined') {
        return;
    }

    var taxYear = window.__F944_TAX_YEAR || new Date().getFullYear();
    var R124 = 0.124;
    var R029 = 0.029;
    var R009 = 0.009;

    var derivedIds = ['f944-l4a2', 'f944-l4b2', 'f944-l4c2', 'f944-l4d2', 'f944-l4e', 'f944-l5', 'f944-l7', 'f944-l9', 'f944-l11', 'f944-l12', 'f944-l13m'];
    var monthIds = ['f944-l13a', 'f944-l13b', 'f944-l13c', 'f944-l13d', 'f944-l13e', 'f944-l13f', 'f944-l13g', 'f944-l13h', 'f944-l13i', 'f944-l13j', 'f944-l13k', 'f944-l13l'];

    var btnOverride = document.getElementById('f944BtnOverride');
    var btnPreview = document.getElementById('f944BtnPreview');
    var btnPrint = document.getElementById('f944BtnPrint');
    var btnPreparer = document.getElementById('f944BtnPreparer');
    var overrideModalEl = document.getElementById('f944OverrideModal');
    var preparerModalEl = document.getElementById('f944PreparerModal');
    var previewModalEl = document.getElementById('f944PreviewModal');
    var previewMount = document.getElementById('f944PreviewMount');
    var previewPrintBtn = document.getElementById('f944PreviewPrint');
    var ack = document.getElementById('f944OverrideAck');
    var okBtn = document.getElementById('f944OverrideOk');

    var bsOverride = bootstrap.Modal.getOrCreateInstance(overrideModalEl);
    var bsPreparer = bootstrap.Modal.getOrCreateInstance(preparerModalEl);
    var bsPreview = bootstrap.Modal.getOrCreateInstance(previewModalEl);

    var editingEnabled = false;

    function parseMoney(s) {
        var n = parseFloat(String(s || '').replace(/,/g, ''));
        return isNaN(n) ? 0 : n;
    }

    function formatMoney(n) {
        return (Math.round(n * 100) / 100).toFixed(2);
    }

    function sanitizeMoney(raw) {
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

    function val(id) {
        var el = document.getElementById(id);
        return el ? el.value : '';
    }

    function setVal(id, v) {
        var el = document.getElementById(id);
        if (el) {
            el.value = v;
        }
    }

    function roundTax(n) {
        return Math.round(n * 100) / 100;
    }

    function recalc944() {
        var l3 = document.getElementById('f944-l3') && document.getElementById('f944-l3').checked;
        var p = parseMoney;
        var l1 = p(val('f944-l1'));
        var l2 = p(val('f944-l2'));
        var l6 = p(val('f944-l6'));
        var l8 = p(val('f944-l8'));
        var l10 = p(val('f944-l10'));

        if (l3) {
            setVal('f944-l4a2', '0.00');
            setVal('f944-l4b2', '0.00');
            setVal('f944-l4c2', '0.00');
            setVal('f944-l4d2', '0.00');
            setVal('f944-l4e', '0.00');
            setVal('f944-l5', formatMoney(l2));
        } else {
            var c4a2 = roundTax(p(val('f944-l4a1')) * R124);
            var c4b2 = roundTax(p(val('f944-l4b1')) * R124);
            var c4c2 = roundTax(p(val('f944-l4c1')) * R029);
            var c4d2 = roundTax(p(val('f944-l4d1')) * R009);
            setVal('f944-l4a2', formatMoney(c4a2));
            setVal('f944-l4b2', formatMoney(c4b2));
            setVal('f944-l4c2', formatMoney(c4c2));
            setVal('f944-l4d2', formatMoney(c4d2));
            var l4e = c4a2 + c4b2 + c4c2 + c4d2;
            setVal('f944-l4e', formatMoney(l4e));
            setVal('f944-l5', formatMoney(l2 + l4e));
        }

        var l5 = p(val('f944-l5'));
        var l7 = l5 + l6;
        setVal('f944-l7', formatMoney(l7));
        var l9 = Math.max(0, l7 - l8);
        setVal('f944-l9', formatMoney(l9));

        if (l9 > l10) {
            setVal('f944-l11', formatMoney(l9 - l10));
            setVal('f944-l12', '');
        } else if (l10 > l9) {
            setVal('f944-l11', '0.00');
            setVal('f944-l12', formatMoney(l10 - l9));
        } else {
            setVal('f944-l11', '0.00');
            setVal('f944-l12', '');
        }

        var msum = 0;
        monthIds.forEach(function (id) {
            msum += p(val(id));
        });
        setVal('f944-l13m', formatMoney(msum));
    }

    function applyLockedUi(locked) {
        document.querySelectorAll('.f944-num').forEach(function (el) {
            var isDeriv = derivedIds.indexOf(el.id) !== -1;
            el.readOnly = isDeriv || locked;
            el.classList.toggle('bg-light', isDeriv);
        });
        document.querySelectorAll('.f944-cb').forEach(function (el) {
            el.disabled = locked;
        });
    }

    function setEditing(on) {
        editingEnabled = on;
        applyLockedUi(!on);
        btnOverride.textContent = on ? 'Enable calculations again' : 'Override calculations';
    }

    document.querySelectorAll('.f944-num').forEach(function (el) {
        el.addEventListener('input', function () {
            if (!editingEnabled || derivedIds.indexOf(el.id) !== -1) {
                return;
            }
            el.value = sanitizeMoney(el.value);
        });
        el.addEventListener('blur', function () {
            if (!editingEnabled || derivedIds.indexOf(el.id) !== -1) {
                return;
            }
            el.value = formatMoney(parseMoney(el.value));
            recalc944();
        });
    });

    document.getElementById('f944-l3').addEventListener('change', function () {
        if (editingEnabled) {
            recalc944();
        }
    });

    btnOverride.addEventListener('click', function () {
        if (editingEnabled) {
            recalc944();
            setEditing(false);
            return;
        }
        ack.checked = false;
        okBtn.disabled = true;
        bsOverride.show();
    });

    ack.addEventListener('change', function () {
        okBtn.disabled = !ack.checked;
    });

    okBtn.addEventListener('click', function () {
        bsOverride.hide();
        setEditing(true);
    });

    overrideModalEl.addEventListener('hidden.bs.modal', function () {
        ack.checked = false;
        okBtn.disabled = true;
    });

    btnPreparer.addEventListener('click', function () {
        bsPreparer.show();
    });

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function splitMoneyDisplay(v) {
        var f = formatMoney(parseMoney(v));
        var p = f.split('.');
        return { d: p[0] || '0', c: (p[1] || '00').slice(0, 2) };
    }

    function moneyBoxes(v) {
        var x = splitMoneyDisplay(v);
        return '<span class="f944-m-d">' + escapeHtml(x.d) + '</span><span class="f944-m-dot">.</span><span class="f944-m-c">' + escapeHtml(x.c) + '</span>';
    }

    /** Match on-screen blanks: line 12 is empty when there is a balance due (not 0.00 on print). */
    function moneyBoxesOptional(v) {
        if (String(v == null ? '' : v).trim() === '') {
            return '<span class="f944-m-d f944-m-empty">&nbsp;</span><span class="f944-m-dot">.</span><span class="f944-m-c f944-m-empty">&nbsp;&nbsp;</span>';
        }
        return moneyBoxes(v);
    }

    function einBoxes(ein) {
        var d = String(ein || '').replace(/\D/g, '').slice(0, 9);
        while (d.length < 9) {
            d += ' ';
        }
        var html = '';
        for (var i = 0; i < 9; i++) {
            var ch = d.charAt(i).trim();
            html += '<span class="f944-ein">' + escapeHtml(ch) + '</span>';
        }
        return html;
    }

    function employerMetaFromServer() {
        var e = window.__F944_EMPLOYER || {};
        function t(v) {
            return String(v == null ? '' : v);
        }
        return {
            ein: t(e.ein),
            name: t(e.legal_name),
            trade: t(e.trade_name),
            addr: t(e.address_line1),
            city: t(e.city),
            st: t(e.state_code),
            zip: t(e.zip_code),
        };
    }

    function collectSnapshot() {
        recalc944();
        var m = employerMetaFromServer();
        var snap = { year: taxYear, meta: m, lines: {}, checks: {} };
        document.querySelectorAll('.f944-num').forEach(function (el) {
            if (el.id) {
                snap.lines[el.id] = el.value;
            }
        });
        snap.checks.l3 = document.getElementById('f944-l3').checked;
        var r13 = document.querySelector('input[name="f944_l13opt"]:checked');
        snap.l13opt = r13 ? r13.value : 'under';
        var r12 = document.querySelector('input[name="f944_l12opt"]:checked');
        snap.l12opt = r12 ? r12.value : '';
        return snap;
    }

    function chk(b) {
        return b ? 'X' : '\u00a0';
    }

    function pageShell(inner) {
        return '<div class="f944-print-page">' + inner + '</div>';
    }

    function wm() {
        return '<div class="f944-wm">Not Updated — Do NOT File</div>';
    }

    function buildPage1(s) {
        var m = s.meta;
        var y = s.year;
        var L = s.lines;
        var inner = ''
            + wm()
            + '<div class="f944-inner">'
            + '<div class="f944-head"><span class="f944-formno">Form 944</span> <span class="f944-sub">Employer&apos;s ANNUAL Federal Tax Return</span> <span class="f944-yr">' + escapeHtml(String(y)) + '</span></div>'
            + '<div class="f944-einrow"><span class="f944-lab">Employer identification number (EIN)</span><div class="f944-einrow-b">' + einBoxes(m.ein) + '</div></div>'
            + '<div class="f944-fieldrow"><span class="f944-lab">Name (not your trade name)</span><div class="f944-field">' + escapeHtml(m.name) + '</div></div>'
            + '<div class="f944-fieldrow"><span class="f944-lab">Trade name (if any)</span><div class="f944-field">' + escapeHtml(m.trade) + '</div></div>'
            + '<div class="f944-fieldrow"><span class="f944-lab">Address</span><div class="f944-field">' + escapeHtml(m.addr) + '</div></div>'
            + '<div class="f944-fieldrow"><span class="f944-lab">City, state, ZIP code</span><div class="f944-field">' + escapeHtml([m.city, m.st, m.zip].filter(Boolean).join(', ')) + '</div></div>'
            + '<div class="f944-part">Part 1: Answer these questions for ' + escapeHtml(String(y)) + '</div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>1</b> Wages, tips, and other compensation</span><span class="f944-line-r">' + moneyBoxes(L['f944-l1']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>2</b> Federal income tax withheld from wages, tips, and other compensation</span><span class="f944-line-r">' + moneyBoxes(L['f944-l2']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l">' + chk(s.checks.l3) + ' <b>3</b> If no wages, tips, and other compensation are subject to social security or Medicare tax, check here and go to line 5.</span><span class="f944-line-r"></span></div>'
            + '<div class="f944-l4title">Line 4: Taxable social security and Medicare wages and tips</div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>4a</b> Taxable social security wages</span><span class="f944-line-r f944-tri">' + moneyBoxes(L['f944-l4a1']) + ' <span class="f944-x">×</span> 0.124 = ' + moneyBoxes(L['f944-l4a2']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>4b</b> Taxable social security tips</span><span class="f944-line-r f944-tri">' + moneyBoxes(L['f944-l4b1']) + ' <span class="f944-x">×</span> 0.124 = ' + moneyBoxes(L['f944-l4b2']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>4c</b> Taxable Medicare wages &amp; tips</span><span class="f944-line-r f944-tri">' + moneyBoxes(L['f944-l4c1']) + ' <span class="f944-x">×</span> 0.029 = ' + moneyBoxes(L['f944-l4c2']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>4d</b> Taxable wages &amp; tips subject to Additional Medicare Tax withholding</span><span class="f944-line-r f944-tri">' + moneyBoxes(L['f944-l4d1']) + ' <span class="f944-x">×</span> 0.009 = ' + moneyBoxes(L['f944-l4d2']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>4e</b> Total social security and Medicare taxes (add column 2 from lines 4a through 4d)</span><span class="f944-line-r">' + moneyBoxes(L['f944-l4e']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>5</b> Total taxes before adjustments (add lines 2 and 4e)</span><span class="f944-line-r">' + moneyBoxes(L['f944-l5']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>6</b> Current year&apos;s adjustments</span><span class="f944-line-r">' + moneyBoxes(L['f944-l6']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>7</b> Total taxes after adjustments (combine lines 5 and 6)</span><span class="f944-line-r">' + moneyBoxes(L['f944-l7']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>8</b> Qualified small business payroll tax credit for increasing research activities</span><span class="f944-line-r">' + moneyBoxes(L['f944-l8']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>9</b> Total taxes after adjustments and nonrefundable credits (subtract line 8 from line 7)</span><span class="f944-line-r">' + moneyBoxes(L['f944-l9']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>10</b> Total deposits for this year, including overpayment applied from a prior year</span><span class="f944-line-r">' + moneyBoxes(L['f944-l10']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>11</b> Balance due (if line 9 is more than line 10, enter the difference)</span><span class="f944-line-r">' + moneyBoxes(L['f944-l11']) + '</span></div>'
            + '<div class="f944-line"><span class="f944-line-l"><b>12</b> Overpayment (if line 10 is more than line 9, enter the difference)</span><span class="f944-line-r">' + moneyBoxesOptional(L['f944-l12']) + '</span></div>'
            + '<div class="f944-helpt">Check one: ' + (s.l12opt === 'next' ? 'X' : '\u00a0') + ' Apply to next return &nbsp; ' + (s.l12opt === 'refund' ? 'X' : '\u00a0') + ' Send a refund</div>'
            + '<div class="f944-footer">Page 1 &nbsp;&nbsp; Form 944 (' + escapeHtml(String(y)) + ')</div>'
            + '</div>';
        return pageShell(inner);
    }

    function buildPage2(s) {
        var m = s.meta;
        var y = s.year;
        var L = s.lines;
        var under = s.l13opt === 'under';
        var inner = ''
            + wm()
            + '<div class="f944-inner">'
            + '<div class="f944-minihead">Name (not your trade name): ' + escapeHtml(m.name) + ' &nbsp;&nbsp; EIN: ' + escapeHtml(m.ein) + '</div>'
            + '<div class="f944-part">Part 2: Tell us about your deposit schedule and tax liability for ' + escapeHtml(String(y)) + '</div>'
            + '<div class="f944-helpt mb-1"><b>13</b> Check one:</div>'
            + '<div class="f944-helpt">' + chk(under) + ' Line 9 is less than $2,500. Go to Part 3.</div>'
            + '<div class="f944-helpt mb-2">' + chk(!under) + ' Line 9 is $2,500 or more.</div>'
            + '<table class="f944-mgrid"><tr><th>13a Jan</th><th>13d Apr</th><th>13g Jul</th><th>13j Oct</th></tr><tr>'
            + '<td>' + moneyBoxes(L['f944-l13a']) + '</td><td>' + moneyBoxes(L['f944-l13d']) + '</td><td>' + moneyBoxes(L['f944-l13g']) + '</td><td>' + moneyBoxes(L['f944-l13j']) + '</td></tr>'
            + '<tr><th>13b Feb</th><th>13e May</th><th>13h Aug</th><th>13k Nov</th></tr><tr>'
            + '<td>' + moneyBoxes(L['f944-l13b']) + '</td><td>' + moneyBoxes(L['f944-l13e']) + '</td><td>' + moneyBoxes(L['f944-l13h']) + '</td><td>' + moneyBoxes(L['f944-l13k']) + '</td></tr>'
            + '<tr><th>13c Mar</th><th>13f Jun</th><th>13i Sep</th><th>13l Dec</th></tr><tr>'
            + '<td>' + moneyBoxes(L['f944-l13c']) + '</td><td>' + moneyBoxes(L['f944-l13f']) + '</td><td>' + moneyBoxes(L['f944-l13i']) + '</td><td>' + moneyBoxes(L['f944-l13l']) + '</td></tr></table>'
            + '<div class="f944-line mt-2"><span class="f944-line-l"><b>13m</b> Total liability for year (add lines 13a through 13l). Total must equal line 9.</span><span class="f944-line-r">' + moneyBoxes(L['f944-l13m']) + '</span></div>'
            + '<div class="f944-part mt-3">Part 3: Tell us about your business.</div>'
            + '<div class="f944-helpt mb-2"><b>14</b> If your business has closed or you stopped paying wages, check here and enter the final date you paid wages (MM/DD/YYYY).</div>'
            + '<div class="f944-part">Part 4: May we speak with your third-party designee?</div>'
            + '<div class="f944-helpt mb-2">Yes / No &nbsp; (complete on official form)</div>'
            + '<div class="f944-part">Part 5: Sign here. You MUST complete both pages of Form 944 and SIGN it.</div>'
            + '<div class="f944-sigbox"></div>'
            + '<div class="f944-part">Paid Preparer Use Only</div>'
            + '<table class="f944-prep"><tr><td>Preparer&apos;s name</td><td>PTIN</td></tr><tr><td colspan="2">&nbsp;</td></tr></table>'
            + '<div class="f944-footer">Page 2 &nbsp;&nbsp; Form 944 (' + escapeHtml(String(y)) + ')</div>'
            + '</div>';
        return pageShell(inner);
    }

    function buildPage3(s) {
        var m = s.meta;
        var y = s.year;
        var l11Due = parseMoney(s.lines['f944-l11']);
        var pay = splitMoneyDisplay(l11Due > 0 ? s.lines['f944-l11'] : '0');
        var inner = ''
            + wm()
            + '<div class="f944-inner">'
            + '<h2 class="f944-vh">Form 944-V, Payment Voucher</h2>'
            + '<div class="f944-v2col">'
            + '<div class="f944-vcol"><p class="f944-vp"><b>Purpose of Form</b> Use Form 944-V when making a payment with Form 944. Using the voucher helps credit your payment promptly and accurately.</p>'
            + '<p class="f944-vp"><b>Making Payments With Form 944</b> Enter your payment amount in Box 2. Make your check or money order payable to &quot;United States Treasury.&quot; Don&apos;t staple or attach this voucher to your payment or Form 944.</p>'
            + '<div class="f944-caution"><b>CAUTION</b> Use Form 944-V only when paying a balance due on Form 944 by check or money order.</div></div>'
            + '<div class="f944-vcol"><p class="f944-vp"><b>Specific Instructions</b></p>'
            + '<p class="f944-vp"><b>Box 1.</b> Enter your EIN.</p><p class="f944-vp"><b>Box 2.</b> Enter the amount of your payment.</p>'
            + '<p class="f944-vp"><b>Box 3–4.</b> Enter your business name and address as shown on Form 944.</p></div></div>'
            + '<div class="f944-detach">Detach Here and Mail With Your Payment and Form 944</div>'
            + '<div class="f944-vouch">'
            + '<div class="f944-vleft">Form <b>944-V</b><br>Department of the Treasury<br>Internal Revenue Service</div>'
            + '<div class="f944-vmid"><b>Payment Voucher</b><br><span class="f944-vsmall">Don&apos;t staple this voucher or your payment to Form 944.</span></div>'
            + '<div class="f944-vright"><div class="f944-omb">OMB No. 1545-0029</div><div class="f944-vyear">' + escapeHtml(String(y)) + '</div></div></div>'
            + '<div class="f944-vbox"><b>1</b> Employer identification number (EIN)<div class="f944-einrow-b" style="margin-top:4px">' + einBoxes(m.ein) + '</div></div>'
            + '<div class="f944-vbox"><b>2</b> Enter the amount of your payment<br><span class="f944-vamt-lab">Dollars</span> <span class="f944-vamt">' + escapeHtml(pay.d) + '</span> '
            + '<span class="f944-vamt-lab">Cents</span> <span class="f944-vamt-c">' + escapeHtml(pay.c) + '</span></div>'
            + '<div class="f944-vbox"><b>3</b> Enter your business name<br><div class="f944-vaddr">' + escapeHtml(m.name) + '</div></div>'
            + '<div class="f944-vbox"><b>4</b> Enter your address and city, state, and ZIP code<br><div class="f944-vaddr">' + escapeHtml(m.addr) + '<br>' + escapeHtml([m.city, m.st, m.zip].filter(Boolean).join(' ')) + '</div></div>'
            + '<div class="f944-footer">Page 3 &nbsp;&nbsp; Form 944-V (' + escapeHtml(String(y)) + ')</div>'
            + '</div>';
        return pageShell(inner);
    }

    function buildOfficial944Pack() {
        var s = collectSnapshot();
        return buildPage1(s) + buildPage2(s) + buildPage3(s);
    }

    var printCss = ''
        + '<style>'
        + '*{box-sizing:border-box}'
        + '.f944-print-root{font-family:system-ui,sans-serif;padding:12px;background:#e9e9e9;min-height:100%}'
        + '.f944-doc{font-family:"Times New Roman",Times,serif;font-size:10pt;color:#000}'
        + '.f944-print-page{position:relative;width:8.5in;min-height:10.8in;margin:0 auto 16px;padding:0.42in 0.48in 0.45in;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.2);page-break-after:always}'
        + '.f944-print-page:last-child{page-break-after:auto;margin-bottom:0}'
        + '.f944-inner{position:relative;z-index:2}'
        + '.f944-wm{position:absolute;left:0;right:0;top:36%;text-align:center;font-size:32pt;font-weight:bold;color:#bbb;opacity:0.42;transform:rotate(-20deg);pointer-events:none;z-index:1;white-space:nowrap}'
        + '.f944-head{margin-bottom:10px;border-bottom:2px solid #000;padding-bottom:6px}'
        + '.f944-formno{font-size:16pt;font-weight:bold}'
        + '.f944-sub{font-size:10pt;font-weight:bold}'
        + '.f944-yr{float:right;font-size:14pt;font-weight:bold}'
        + '.f944-einrow{display:flex;align-items:center;gap:8px;margin:6px 0;border-bottom:1px solid #000;padding-bottom:4px}'
        + '.f944-einrow-b{display:flex;flex-wrap:nowrap;gap:1px}'
        + '.f944-ein{display:inline-flex;width:16px;height:20px;border:1px solid #000;align-items:center;justify-content:center;font-size:9pt}'
        + '.f944-lab{font-weight:bold;min-width:1.4in}'
        + '.f944-fieldrow{display:flex;align-items:center;border-bottom:1px solid #000;padding:3px 0;min-height:22px}'
        + '.f944-field{flex:1;border-bottom:1px dotted #666;min-height:18px;padding:0 4px}'
        + '.f944-part{font-weight:bold;font-size:10.5pt;margin:12px 0 6px;border-bottom:1px solid #000;padding-bottom:2px}'
        + '.f944-l4title{font-weight:bold;margin:8px 0 4px;font-size:9.5pt}'
        + '.f944-helpt{font-size:8.5pt;margin:2px 0 4px}'
        + '.f944-line{display:flex;justify-content:space-between;align-items:flex-end;border-bottom:1px dotted #333;padding:3px 0;gap:8px}'
        + '.f944-line-l{flex:1;font-size:9pt;line-height:1.25}'
        + '.f944-line-r{flex-shrink:0;text-align:right;font-variant-numeric:tabular-nums}'
        + '.f944-m-d{border:1px solid #000;min-width:1.1in;display:inline-block;text-align:right;padding:1px 4px}'
        + '.f944-m-dot{font-weight:bold}'
        + '.f944-m-c{border:1px solid #000;width:0.48in;display:inline-block;text-align:center;padding:1px}'
        + '.f944-m-empty{opacity:0.35}'
        + '.f944-cbx{border:1px solid #000;width:1.4rem;height:1.1rem;display:inline-flex;align-items:center;justify-content:center;font-weight:bold}'
        + '.f944-tri{font-size:8.5pt;white-space:nowrap}'
        + '.f944-x{font-weight:bold;margin:0 2px}'
        + '.f944-footer{margin-top:14px;font-size:8pt;color:#333}'
        + '.f944-minihead{font-size:9pt;margin-bottom:8px;border:1px solid #000;padding:4px}'
        + '.f944-mgrid{width:100%;border-collapse:collapse;font-size:9pt;margin:8px 0}'
        + '.f944-mgrid th,.f944-mgrid td{border:1px solid #333;padding:4px;text-align:center}'
        + '.f944-sigbox{border:1px solid #000;height:2rem;margin:8px 0}'
        + '.f944-prep{width:100%;border-collapse:collapse;font-size:9pt;margin-top:8px}'
        + '.f944-prep td{border:1px solid #000;padding:4px;width:50%}'
        + '.f944-vh{font-size:12pt;margin:0 0 8px}'
        + '.f944-v2col{display:flex;gap:12px;margin-bottom:10px}'
        + '.f944-vcol{flex:1;font-size:8.5pt;line-height:1.35}'
        + '.f944-vp{margin:0 0 8px}'
        + '.f944-caution{border:1px solid #000;padding:6px;font-size:8.5pt;margin-top:6px}'
        + '.f944-detach{text-align:center;font-size:9pt;font-weight:bold;margin:12px 0;border-top:1px dashed #000;border-bottom:1px dashed #000;padding:6px}'
        + '.f944-vouch{display:flex;justify-content:space-between;align-items:flex-start;border:2px solid #000;padding:8px;margin-bottom:10px}'
        + '.f944-vleft{font-size:9pt}'
        + '.f944-vmid{text-align:center;flex:1}'
        + '.f944-vsmall{font-size:8pt;font-weight:normal}'
        + '.f944-vright{text-align:right}'
        + '.f944-omb{font-size:8pt;border:1px solid #000;padding:2px 6px;display:inline-block}'
        + '.f944-vyear{font-size:18pt;font-weight:bold;border:2px solid #000;padding:4px 12px;margin-top:4px;display:inline-block}'
        + '.f944-vbox{border:1px solid #000;padding:8px;margin-bottom:8px}'
        + '.f944-vamt-lab{font-size:8pt}'
        + '.f944-vamt{border:1px solid #000;display:inline-block;min-width:2.2in;text-align:right;padding:2px 6px;margin:0 6px 0 2px}'
        + '.f944-vamt-c{border:1px solid #000;display:inline-block;width:2.4rem;text-align:center;padding:2px}'
        + '.f944-vaddr{margin-top:6px;min-height:2.5rem;border:1px dotted #666;padding:4px;font-family:ui-monospace,monospace}'
        + '@media print{.f944-print-root{background:#fff;padding:0}.f944-print-page{box-shadow:none;margin:0;page-break-after:always}.f944-print-page:last-child{page-break-after:auto}}'
        + '</style>';

    function wrapPrintDocument(innerBody) {
        return '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Form 944 — ' + escapeHtml(String(taxYear)) + '</title>'
            + printCss
            + '</head><body><div class="f944-print-root"><div class="f944-doc">' + innerBody + '</div></div></body></html>';
    }

    btnPreview.addEventListener('click', function () {
        previewMount.innerHTML = '<div class="f944-print-root">' + printCss + '<div class="f944-doc">' + buildOfficial944Pack() + '</div></div>';
        bsPreview.show();
    });

    btnPrint.addEventListener('click', function () {
        var w = window.open('', '_blank');
        if (!w) {
            return;
        }
        w.document.open();
        w.document.write(wrapPrintDocument(buildOfficial944Pack()));
        w.document.close();
        w.focus();
        setTimeout(function () { w.print(); }, 300);
    });

    if (previewPrintBtn) {
        previewPrintBtn.addEventListener('click', function () {
            var w = window.open('', '_blank');
            if (!w) {
                return;
            }
            var inner = previewMount.querySelector('.f944-doc');
            var html = wrapPrintDocument(inner ? inner.innerHTML : buildOfficial944Pack());
            w.document.open();
            w.document.write(html);
            w.document.close();
            w.focus();
            setTimeout(function () { w.print(); }, 300);
        });
    }

    applyLockedUi(true);
    recalc944();
})();
</script>
