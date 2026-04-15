<script>
(function () {
    if (typeof bootstrap === 'undefined') {
        return;
    }

    var taxYear = window.__F940_TAX_YEAR || new Date().getFullYear();
    var usStates = window.__F940_STATES || [];

    var btnOverride = document.getElementById('f940BtnOverride');
    var btnPreview = document.getElementById('f940BtnPreview');
    var btnPrint = document.getElementById('f940BtnPrint');
    var btnPreparer = document.getElementById('f940BtnPreparer');
    var overrideModalEl = document.getElementById('f940OverrideModal');
    var preparerModalEl = document.getElementById('f940PreparerModal');
    var previewModalEl = document.getElementById('f940PreviewModal');
    var previewMount = document.getElementById('f940PreviewMount');
    var previewPrintBtn = document.getElementById('f940PreviewPrint');
    var ack = document.getElementById('f940OverrideAck');
    var okBtn = document.getElementById('f940OverrideOk');

    var bsOverride = bootstrap.Modal.getOrCreateInstance(overrideModalEl);
    var bsPreparer = bootstrap.Modal.getOrCreateInstance(preparerModalEl);
    var bsPreview = bootstrap.Modal.getOrCreateInstance(previewModalEl);

    var editingEnabled = false;
    var l1a = document.getElementById('f940-l1a');

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

    function recalc() {
        var l3 = parseMoney(val('f940-l3'));
        var l4 = parseMoney(val('f940-l4'));
        var l5 = parseMoney(val('f940-l5'));
        var l9 = parseMoney(val('f940-l9'));
        var l10 = parseMoney(val('f940-l10'));
        var l11 = parseMoney(val('f940-l11'));
        var l13 = parseMoney(val('f940-l13'));

        var l6 = l4 + l5;
        var l7 = Math.max(0, l3 - l6);
        var l8 = Math.round(l7 * 0.006 * 100) / 100;
        var l12 = l8 + l9 + l10 + l11;

        setVal('f940-l6', formatMoney(l6));
        setVal('f940-l7', formatMoney(l7));
        setVal('f940-l8', formatMoney(l8));
        setVal('f940-l12', formatMoney(l12));

        if (l12 > l13) {
            setVal('f940-l14', formatMoney(l12 - l13));
            setVal('f940-l15', '');
        } else if (l13 > l12) {
            setVal('f940-l14', '0.00');
            setVal('f940-l15', formatMoney(l13 - l12));
        } else {
            setVal('f940-l14', '0.00');
            setVal('f940-l15', '');
        }

        var l16sum = parseMoney(val('f940-l16a')) + parseMoney(val('f940-l16b')) + parseMoney(val('f940-l16c')) + parseMoney(val('f940-l16d'));
        setVal('f940-l17', l16sum > 0 ? formatMoney(l16sum) : '');
    }

    function applyLockedUi(locked) {
        document.querySelectorAll('.f940-num, .f940-txt').forEach(function (el) {
            el.readOnly = locked;
            el.classList.toggle('bg-light', locked);
        });
        document.querySelectorAll('.f940-cb').forEach(function (el) {
            el.disabled = locked;
        });
        var sel = document.getElementById('f940-stateSuta');
        if (sel) {
            sel.disabled = locked;
        }
    }

    function setEditing(on) {
        editingEnabled = on;
        applyLockedUi(!on);
        btnOverride.textContent = on ? 'Enable calculations again' : 'Override calculations';
    }

    var derivedLineIds = ['f940-l6', 'f940-l7', 'f940-l8', 'f940-l12', 'f940-l14', 'f940-l15', 'f940-l17'];

    document.querySelectorAll('.f940-num').forEach(function (el) {
        el.addEventListener('input', function () {
            if (!editingEnabled) {
                return;
            }
            el.value = sanitizeMoney(el.value);
        });
        el.addEventListener('blur', function () {
            if (!editingEnabled) {
                return;
            }
            el.value = formatMoney(parseMoney(el.value));
            if (derivedLineIds.indexOf(el.id) === -1) {
                recalc();
            }
        });
    });

    if (l1a) {
        l1a.addEventListener('input', function () {
            if (!editingEnabled) {
                return;
            }
            l1a.value = String(l1a.value || '').toUpperCase().replace(/[^A-Z]/g, '').slice(0, 2);
        });
    }

    btnOverride.addEventListener('click', function () {
        if (editingEnabled) {
            recalc();
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

    function chk(snap, id) {
        return snap.checks[id] ? 'X' : '\u00a0';
    }

    function splitMoneyDisplay(v) {
        var f = formatMoney(parseMoney(v));
        var p = f.split('.');
        return { d: p[0] || '0', c: (p[1] || '00').slice(0, 2) };
    }

    function moneyBoxes(v) {
        var p = splitMoneyDisplay(v);
        return '<span class="f940-m-d">' + escapeHtml(p.d) + '</span>'
            + '<span class="f940-m-dot">.</span>'
            + '<span class="f940-m-c">' + escapeHtml(p.c) + '</span>';
    }

    function einBoxes(ein) {
        var d = String(ein || '').replace(/\D/g, '').slice(0, 9);
        while (d.length < 9) {
            d += '\u00a0';
        }
        var html = '';
        for (var i = 0; i < 9; i++) {
            html += '<span class="f940-ein">' + escapeHtml(d.charAt(i) === '\u00a0' ? '' : d.charAt(i)) + '</span>';
        }
        return html;
    }

    function employerMetaFromServer() {
        var e = window.__F940_EMPLOYER || {};
        function t(v) {
            return String(v == null ? '' : v);
        }
        return {
            'f940-meta-ein': t(e.ein),
            'f940-meta-name': t(e.legal_name),
            'f940-meta-trade': t(e.trade_name),
            'f940-meta-addr1': t(e.address_line1),
            'f940-meta-city': t(e.city),
            'f940-meta-st': t(e.state_code),
            'f940-meta-zip': t(e.zip_code),
        };
    }

    function collectSnapshot() {
        recalc();
        var snap = { year: taxYear, lines: {}, checks: {}, meta: employerMetaFromServer() };
        document.querySelectorAll('.f940-num').forEach(function (el) {
            if (el.id) {
                snap.lines[el.id] = el.value;
            }
        });
        if (l1a) {
            snap.l1a = l1a.value;
        }
        var sel = document.getElementById('f940-stateSuta');
        snap.stateSuta = sel ? sel.value : '';
        document.querySelectorAll('.f940-cb').forEach(function (el) {
            if (el.id) {
                snap.checks[el.id] = el.type === 'checkbox' ? el.checked : el.checked;
            }
        });
        var r15 = document.querySelector('input[name="f940_l15opt"]:checked');
        snap.l15opt = r15 ? r15.value : '';
        return snap;
    }

    function pageShell(inner) {
        return '<div class="f940-print-page">' + inner + '</div>';
    }

    function watermark() {
        return '<div class="f940-wm">Not Updated — Do NOT File</div>';
    }

    function buildPage1(s) {
        var m = s.meta;
        var y = s.year;
        var inner = ''
            + watermark()
            + '<div class="f940-inner">'
            + '<div class="f940-toprow">'
            + '<div><span class="f940-formno">Form 940</span> <span class="f940-fory">for ' + escapeHtml(String(y)) + '</span></div>'
            + '<div class="f940-typebox">'
            + '<div class="f940-type-title">Type of return (check all that apply)</div>'
            + '<div class="f940-type-line">' + chk(s, 'f940-ret-a') + ' a. Amended</div>'
            + '<div class="f940-type-line">' + chk(s, 'f940-ret-b') + ' b. Successor employer</div>'
            + '<div class="f940-type-line">' + chk(s, 'f940-ret-c') + ' c. No payments to employees in ' + escapeHtml(String(y)) + '</div>'
            + '<div class="f940-type-line">' + chk(s, 'f940-ret-d') + ' d. Final: Business closed or stopped paying wages</div>'
            + '</div></div>'
            + '<div class="f940-einrow"><span class="f940-lab">Employer identification number (EIN)</span><div class="f940-einrow-b">' + einBoxes(m['f940-meta-ein']) + '</div></div>'
            + '<div class="f940-fieldrow"><span class="f940-lab">Name (not your trade name)</span><div class="f940-field">' + escapeHtml(m['f940-meta-name'] || '') + '</div></div>'
            + '<div class="f940-fieldrow"><span class="f940-lab">Trade name (if any)</span><div class="f940-field">' + escapeHtml(m['f940-meta-trade'] || '') + '</div></div>'
            + '<div class="f940-fieldrow"><span class="f940-lab">Address</span><div class="f940-field">' + escapeHtml(m['f940-meta-addr1'] || '') + '</div></div>'
            + '<div class="f940-fieldrow f940-addr2"><span class="f940-lab">City, state, ZIP code</span>'
            + '<div class="f940-field">' + escapeHtml([m['f940-meta-city'], m['f940-meta-st'], m['f940-meta-zip']].filter(Boolean).join(', ')) + '</div></div>'

            + '<div class="f940-part">Part 1: Tell us about your return</div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>1a</b> If you had to pay state unemployment tax in one state only, enter the state abbreviation.</span>'
            + '<span class="f940-line-r f940-st">' + escapeHtml(s.l1a || '') + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>1b</b> If you had to pay state unemployment tax in more than one state, you are a multi-state employer. Check here. Complete Schedule A (Form 940).</span>'
            + '<span class="f940-line-r f940-cbx">' + chk(s, 'f940-l1b') + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>2</b> If you paid wages in a state that is subject to CREDIT REDUCTION. Check here. Complete Schedule A (Form 940).</span>'
            + '<span class="f940-line-r f940-cbx">' + chk(s, 'f940-l2') + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l">State in which you were required to pay state unemployment tax this year</span>'
            + '<span class="f940-line-r f940-st">' + escapeHtml(s.stateSuta || '') + '</span></div>'

            + '<div class="f940-part">Part 2: Determine your FUTA tax before adjustments</div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>3</b> Total payments to all employees</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l3']) + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>4</b> Payments exempt from FUTA tax</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l4']) + '</span></div>'
            + '<div class="f940-sub4">Check all that apply: '
            + chk(s, 'f940-l4a') + ' 4a Fringe benefits &nbsp; '
            + chk(s, 'f940-l4b') + ' 4b Group-term life insurance &nbsp; '
            + chk(s, 'f940-l4c') + ' 4c Retirement/Pension &nbsp; '
            + chk(s, 'f940-l4d') + ' 4d Dependent care &nbsp; '
            + chk(s, 'f940-l4e') + ' 4e Other'
            + '</div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>5</b> Total of payments made to each employee in excess of $7,000</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l5']) + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>6</b> Subtotal (line 4 + line 5 = line 6)</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l6']) + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>7</b> Total taxable FUTA wages (line 3 - line 6 = line 7)</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l7']) + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>8</b> FUTA tax before adjustments (line 7 x .006 = line 8)</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l8']) + '</span></div>'

            + '<div class="f940-part">Part 3: Determine your adjustments</div>'
            + '<div class="f940-line"><span class="f940-line-l">' + chk(s, 'f940-l9cb') + ' <b>9</b> If ALL of the taxable FUTA wages you paid were excluded from state unemployment tax, multiply line 7 by .054 (line 7 x .054 = line 9). Then go to line 12.</span>'
            + '<span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l9']) + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l">' + chk(s, 'f940-l10cb') + ' <b>10</b> If SOME of the taxable FUTA wages you paid were excluded from state unemployment tax, OR you paid ANY state unemployment tax late, enter amount from worksheet line 7.</span>'
            + '<span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l10']) + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>11</b> If credit reduction applies, enter the total from Schedule A (Form 940)</span>'
            + '<span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l11']) + '</span></div>'

            + '<div class="f940-part">Part 4: Determine your FUTA tax and balance due or overpayment</div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>12</b> Total FUTA tax after adjustments (lines 8 + 9 + 10 + 11 = line 12)</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l12']) + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>13</b> FUTA tax deposited for the year, including any payment applied from a prior year</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l13']) + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>14</b> Balance due (If line 12 is more than line 13, enter the difference on line 14.)</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l14']) + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>15</b> Overpayment (If line 13 is more than line 12, enter the difference on line 15 and check a box below.)</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l15']) + '</span></div>'
            + '<div class="f940-sub4">Check one: '
            + (s.l15opt === 'next' ? 'X' : '\u00a0') + ' Apply to next return &nbsp;&nbsp; '
            + (s.l15opt === 'refund' ? 'X' : '\u00a0') + ' Send a refund'
            + '</div>'
            + '<div class="f940-footer">Page 1 &nbsp;&nbsp; Form 940 (' + escapeHtml(String(y)) + ')</div>'
            + '</div>';
        return pageShell(inner);
    }

    function buildPage2(s) {
        var y = s.year;
        var inner = ''
            + watermark()
            + '<div class="f940-inner">'
            + '<div class="f940-minihead">Name (not your trade name): ' + escapeHtml(s.meta['f940-meta-name'] || '') + ' &nbsp;&nbsp; EIN: ' + escapeHtml(s.meta['f940-meta-ein'] || '') + '</div>'
            + '<div class="f940-part">Part 5: Report your FUTA tax liability by quarter only if line 12 is more than $500. If not, go to Part 6.</div>'
            + '<p class="f940-note">This section is used only when line 12 is more than $500. See the instructions for details.</p>'
            + '<div class="f940-line"><span class="f940-line-l"><b>16a</b> 1st quarter (January 1 - March 31)</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l16a']) + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>16b</b> 2nd quarter (April 1 - June 30)</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l16b']) + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>16c</b> 3rd quarter (July 1 - September 30)</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l16c']) + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>16d</b> 4th quarter (October 1 - December 31)</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l16d']) + '</span></div>'
            + '<div class="f940-line"><span class="f940-line-l"><b>17</b> Total tax liability for the year (lines 16a + 16b + 16c + 16d = line 17). Total must equal line 12.</span><span class="f940-line-r f940-mnum">' + moneyBoxes(s.lines['f940-l17']) + '</span></div>'

            + '<div class="f940-part">Part 6: May we speak with your third-party designee?</div>'
            + '<div class="f940-line"><span class="f940-line-l">Designee\'s name and phone</span><span class="f940-line-r f940-blank">&nbsp;</span></div>'
            + '<div class="f940-line"><span class="f940-line-l">5-digit PIN</span><span class="f940-line-r f940-pin"><span class="f940-ein"></span><span class="f940-ein"></span><span class="f940-ein"></span><span class="f940-ein"></span><span class="f940-ein"></span></span></div>'
            + '<div class="f940-sub4">' + '\u00a0' + ' Yes &nbsp;&nbsp;&nbsp; ' + '\u00a0' + ' No</div>'

            + '<div class="f940-part">Part 7: Sign here. You MUST complete both pages of this form and SIGN it.</div>'
            + '<div class="f940-sigbox">Sign your name here</div>'
            + '<div class="f940-fieldrow"><span class="f940-lab">Date</span><div class="f940-field">&nbsp;</div></div>'
            + '<div class="f940-fieldrow"><span class="f940-lab">Print your name here</span><div class="f940-field">&nbsp;</div></div>'
            + '<div class="f940-fieldrow"><span class="f940-lab">Print your title here</span><div class="f940-field">&nbsp;</div></div>'
            + '<div class="f940-fieldrow"><span class="f940-lab">Best daytime phone</span><div class="f940-field">&nbsp;</div></div>'

            + '<div class="f940-part">Paid Preparer Use Only</div>'
            + '<table class="f940-prep"><tr><td>Preparer\'s name</td><td>PTIN</td></tr><tr><td colspan="2">&nbsp;</td></tr>'
            + '<tr><td>Firm\'s name</td><td>Firm\'s EIN</td></tr><tr><td colspan="2">&nbsp;</td></tr>'
            + '<tr><td colspan="2">Firm\'s address</td></tr><tr><td colspan="2">&nbsp;</td></tr>'
            + '<tr><td>Phone</td><td>Check if self-employed</td></tr></table>'
            + '<div class="f940-footer">Page 2 &nbsp;&nbsp; Form 940 (' + escapeHtml(String(y)) + ')</div>'
            + '</div>';
        return pageShell(inner);
    }

    function buildPage3(s) {
        var m = s.meta;
        var y = s.year;
        var pay = splitMoneyDisplay(s.lines['f940-l14']);
        if (parseMoney(s.lines['f940-l14']) <= 0) {
            pay = splitMoneyDisplay(s.lines['f940-l12']);
        }
        var inner = ''
            + watermark()
            + '<div class="f940-inner">'
            + '<h2 class="f940-vh">Form 940-V, Payment Voucher</h2>'
            + '<div class="f940-v2col">'
            + '<div class="f940-vcol">'
            + '<p class="f940-vp"><b>Purpose of Form</b> Complete Form 940-V if you are making a payment with Form 940. We will use this voucher to credit your payment more accurately and efficiently. If you have your return or payment mailed to a different address, follow the instructions in the tax form booklet.</p>'
            + '<p class="f940-vp"><b>Making Payments With Form 940</b> To avoid a penalty, make your check or money order payable to &quot;United States Treasury&quot; and include your EIN, &quot;Form 940,&quot; and &quot;[tax year]&quot; on your check or money order. Enter the amount of your payment in Box 2. Don\'t staple or attach this voucher to your payment or Form 940.</p>'
            + '<div class="f940-caution"><b>CAUTION</b> Use this voucher only if you are making a payment with Form 940. If you are not making a payment, do not use this voucher.</div>'
            + '</div>'
            + '<div class="f940-vcol">'
            + '<p class="f940-vp"><b>Specific Instructions</b></p>'
            + '<p class="f940-vp"><b>Box 1.</b> Enter your employer identification number (EIN).</p>'
            + '<p class="f940-vp"><b>Box 2.</b> Enter the amount of your payment.</p>'
            + '<p class="f940-vp"><b>Box 3.</b> Enter your business name and address exactly as shown on the corresponding lines on Form 940.</p>'
            + '</div></div>'
            + '<div class="f940-detach">Detach Here and Mail With Your Payment and Form 940</div>'
            + '<div class="f940-vouch">'
            + '<div class="f940-vleft">Form <b>940-V</b><br>Department of the Treasury<br>Internal Revenue Service</div>'
            + '<div class="f940-vmid"><b>Payment Voucher</b><br><span class="f940-vsmall">Don\'t staple or attach this voucher to your payment</span></div>'
            + '<div class="f940-vright"><div class="f940-omb">OMB No. 1545-0028</div><div class="f940-vyear">' + escapeHtml(String(y)) + '</div></div>'
            + '</div>'
            + '<div class="f940-vbox"><b>1</b> Employer identification number (EIN)<div class="f940-einrow-b" style="margin-top:4px">' + einBoxes(m['f940-meta-ein']) + '</div></div>'
            + '<div class="f940-vbox"><b>2</b> Amount of your payment<br>'
            + '<span class="f940-vamt-lab">Dollars</span> <span class="f940-vamt">' + escapeHtml(pay.d) + '</span> '
            + '<span class="f940-vamt-lab">Cents</span> <span class="f940-vamt-c">' + escapeHtml(pay.c) + '</span></div>'
            + '<div class="f940-vbox"><b>3</b> Name (as shown on Form 940) and address<br>'
            + '<div class="f940-vaddr">' + escapeHtml(m['f940-meta-name'] || '') + '<br>'
            + escapeHtml(m['f940-meta-addr1'] || '') + '<br>'
            + escapeHtml([m['f940-meta-city'], m['f940-meta-st'], m['f940-meta-zip']].filter(Boolean).join(' ')) + '</div></div>'
            + '<div class="f940-footer">Page 3 &nbsp;&nbsp; Form 940-V (' + escapeHtml(String(y)) + ')</div>'
            + '</div>';
        return pageShell(inner);
    }

    function scheduleStateMark(st, s) {
        if (s.checks['f940-l2'] && st === s.stateSuta) {
            return 'X';
        }
        if (s.checks['f940-l1b'] && st === s.l1a) {
            return 'X';
        }
        return '\u00a0';
    }

    function buildPage4(s) {
        var y = s.year;
        var states = usStates.slice().sort();
        var half = Math.ceil(states.length / 2);
        var col1 = states.slice(0, half);
        var col2 = states.slice(half);
        var rows = '';
        var i;
        for (i = 0; i < half; i++) {
            var a = col1[i];
            var b = col2[i] || '';
            rows += '<tr>';
            rows += '<td class="f940-sch-x">' + scheduleStateMark(a, s) + '</td><td class="f940-sch-st">' + escapeHtml(a) + '</td>'
                + '<td class="f940-sch-w">&nbsp;</td><td class="f940-sch-r">&nbsp;</td><td class="f940-sch-c">&nbsp;</td>';
            if (b) {
                rows += '<td class="f940-sch-x">' + scheduleStateMark(b, s) + '</td><td class="f940-sch-st">' + escapeHtml(b) + '</td>'
                    + '<td class="f940-sch-w">&nbsp;</td><td class="f940-sch-r">&nbsp;</td><td class="f940-sch-c">&nbsp;</td>';
            } else {
                rows += '<td colspan="5"></td>';
            }
            rows += '</tr>';
        }
        var inner = ''
            + watermark()
            + '<div class="f940-inner">'
            + '<div class="f940-sch-title">Schedule A (Form 940) for ' + escapeHtml(String(y)) + ': Multi-State Employer and Credit Reduction Information</div>'
            + '<div class="f940-sch-sub">Department of the Treasury — Internal Revenue Service</div>'
            + '<div class="f940-einrow"><span class="f940-lab">Employer identification number (EIN)</span><div class="f940-einrow-b">' + einBoxes(s.meta['f940-meta-ein']) + '</div></div>'
            + '<div class="f940-fieldrow"><span class="f940-lab">Name (as shown on Form 940)</span><div class="f940-field">' + escapeHtml(s.meta['f940-meta-name'] || '') + '</div></div>'
            + '<p class="f940-sch-inst">Place an &quot;X&quot; in the box of EVERY state in which you had to pay state unemployment tax this year. For each state with a credit reduction rate greater than zero, enter the FUTA taxable wages, multiply by the reduction rate, and enter the credit reduction amount. Don\'t include in the FUTA Taxable Wages box wages that were excluded from state unemployment tax (see the instructions for Step 2). If any states don\'t apply to you, leave them blank.</p>'
            + '<table class="f940-sch-t">'
            + '<tr><th></th><th>State</th><th>FUTA taxable wages</th><th>Reduction rate</th><th>Credit reduction</th>'
            + '<th></th><th>State</th><th>FUTA taxable wages</th><th>Reduction rate</th><th>Credit reduction</th></tr>'
            + rows
            + '</table>'
            + '<div class="f940-footer">Page 4 &nbsp;&nbsp; Schedule A (Form 940) (' + escapeHtml(String(y)) + ')</div>'
            + '</div>';
        return pageShell(inner);
    }

    function buildOfficial940Pack() {
        var s = collectSnapshot();
        return buildPage1(s) + buildPage2(s) + buildPage3(s) + buildPage4(s);
    }

    var printCss = ''
        + '<style>'
        + '*{box-sizing:border-box}'
        + '.f940-print-root{font-family:system-ui,sans-serif;padding:12px;background:#e9e9e9;min-height:100%}'
        + '.f940-doc{font-family:"Times New Roman",Times,serif;font-size:10pt;color:#000}'
        + '.f940-print-page{position:relative;width:8.5in;min-height:10.8in;margin:0 auto 16px;padding:0.42in 0.48in 0.45in;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.2);page-break-after:always}'
        + '.f940-print-page:last-child{page-break-after:auto;margin-bottom:0}'
        + '.f940-inner{position:relative;z-index:2}'
        + '.f940-wm{position:absolute;left:0;right:0;top:36%;text-align:center;font-size:32pt;font-weight:bold;color:#bbb;opacity:0.42;transform:rotate(-20deg);pointer-events:none;z-index:1;white-space:nowrap}'
        + '.f940-toprow{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:8px}'
        + '.f940-formno{font-size:16pt;font-weight:bold}'
        + '.f940-fory{font-size:11pt;font-weight:bold}'
        + '.f940-typebox{border:1px solid #000;padding:6px 8px;min-width:2.6in;font-size:8.5pt}'
        + '.f940-type-title{font-weight:bold;margin-bottom:4px}'
        + '.f940-type-line{margin:2px 0}'
        + '.f940-einrow{display:flex;align-items:center;gap:8px;margin:6px 0;border-bottom:1px solid #000;padding-bottom:4px}'
        + '.f940-einrow-b{display:flex;flex-wrap:nowrap;gap:1px}'
        + '.f940-ein{display:inline-flex;width:16px;height:20px;border:1px solid #000;align-items:center;justify-content:center;font-size:9pt}'
        + '.f940-lab{font-weight:bold;min-width:1.4in}'
        + '.f940-fieldrow{display:flex;align-items:center;border-bottom:1px solid #000;padding:3px 0;min-height:22px}'
        + '.f940-field{flex:1;border-bottom:1px dotted #666;min-height:18px;padding:0 4px}'
        + '.f940-addr2{margin-bottom:10px}'
        + '.f940-part{font-weight:bold;font-size:10.5pt;margin:10px 0 4px;border-bottom:1px solid #000;padding-bottom:2px}'
        + '.f940-line{display:flex;justify-content:space-between;align-items:flex-end;border-bottom:1px dotted #333;padding:3px 0;gap:8px}'
        + '.f940-line-l{flex:1;font-size:9pt;line-height:1.25}'
        + '.f940-line-r{flex-shrink:0;text-align:right}'
        + '.f940-mnum{font-variant-numeric:tabular-nums}'
        + '.f940-m-d{border:1px solid #000;min-width:1.15in;display:inline-block;text-align:right;padding:1px 4px}'
        + '.f940-m-dot{font-weight:bold}'
        + '.f940-m-c{border:1px solid #000;width:0.5in;display:inline-block;text-align:center;padding:1px}'
        + '.f940-st{border:1px solid #000;min-width:2.5rem;text-align:center;padding:1px 6px;font-weight:bold}'
        + '.f940-cbx{border:1px solid #000;width:1.5rem;height:1.25rem;display:inline-flex;align-items:center;justify-content:center;font-weight:bold}'
        + '.f940-sub4{font-size:8.5pt;margin:4px 0 6px 12px}'
        + '.f940-footer{margin-top:14px;font-size:8pt;color:#333}'
        + '.f940-note{font-size:8.5pt;font-style:italic;margin:4px 0 8px}'
        + '.f940-minihead{font-size:9pt;margin-bottom:8px;border:1px solid #000;padding:4px}'
        + '.f940-blank{border:1px solid #000;min-width:2in;min-height:1.1rem;display:inline-block}'
        + '.f940-pin .f940-ein{width:18px}'
        + '.f940-sigbox{border:1px solid #000;height:2.2rem;margin:8px 0}'
        + '.f940-prep{width:100%;border-collapse:collapse;font-size:9pt;margin-top:8px}'
        + '.f940-prep td{border:1px solid #000;padding:4px;width:50%}'
        + '.f940-vh{font-size:12pt;margin:0 0 8px}'
        + '.f940-v2col{display:flex;gap:12px;margin-bottom:10px}'
        + '.f940-vcol{flex:1;font-size:8.5pt;line-height:1.35}'
        + '.f940-vp{margin:0 0 8px}'
        + '.f940-caution{border:1px solid #000;padding:6px;font-size:8.5pt;margin-top:6px}'
        + '.f940-detach{text-align:center;font-size:9pt;font-weight:bold;margin:12px 0;border-top:1px dashed #000;border-bottom:1px dashed #000;padding:6px}'
        + '.f940-vouch{display:flex;justify-content:space-between;align-items:flex-start;border:2px solid #000;padding:8px;margin-bottom:10px}'
        + '.f940-vleft{font-size:9pt}'
        + '.f940-vmid{text-align:center;flex:1}'
        + '.f940-vsmall{font-size:8pt;font-weight:normal}'
        + '.f940-vright{text-align:right}'
        + '.f940-omb{font-size:8pt;border:1px solid #000;padding:2px 6px;display:inline-block}'
        + '.f940-vyear{font-size:18pt;font-weight:bold;border:2px solid #000;padding:4px 12px;margin-top:4px;display:inline-block}'
        + '.f940-vbox{border:1px solid #000;padding:8px;margin-bottom:8px}'
        + '.f940-vamt-lab{font-size:8pt}'
        + '.f940-vamt{border:1px solid #000;display:inline-block;min-width:2.5in;text-align:right;padding:2px 6px;margin:0 6px 0 2px}'
        + '.f940-vamt-c{border:1px solid #000;display:inline-block;width:2.5rem;text-align:center;padding:2px}'
        + '.f940-vaddr{margin-top:6px;min-height:3.5rem;border:1px dotted #666;padding:4px}'
        + '.f940-sch-title{font-size:11pt;font-weight:bold;text-align:center;margin-bottom:4px}'
        + '.f940-sch-sub{text-align:center;font-size:9pt;margin-bottom:8px}'
        + '.f940-sch-inst{font-size:8.5pt;border:1px solid #000;padding:6px;margin:8px 0}'
        + '.f940-sch-t{width:100%;border-collapse:collapse;font-size:8pt}'
        + '.f940-sch-t th,.f940-sch-t td{border:1px solid #333;padding:2px 4px}'
        + '.f940-sch-x{width:1.2rem;text-align:center;font-weight:bold}'
        + '.f940-sch-st{width:2rem;font-weight:bold}'
        + '.f940-sch-w,.f940-sch-r,.f940-sch-c{text-align:right}'
        + '@media print{'
        + '.f940-print-root{background:#fff;padding:0}'
        + '.f940-print-page{box-shadow:none;margin:0;page-break-after:always}'
        + '.f940-print-page:last-child{page-break-after:auto}'
        + '} '
        + '</style>';

    function wrapPrintDocument(innerBody) {
        return '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Form 940 — ' + escapeHtml(String(taxYear)) + '</title>'
            + printCss
            + '</head><body><div class="f940-print-root"><div class="f940-doc">' + innerBody + '</div></div></body></html>';
    }

    btnPreview.addEventListener('click', function () {
        previewMount.innerHTML = '<div class="f940-print-root">' + printCss + '<div class="f940-doc">' + buildOfficial940Pack() + '</div></div>';
        bsPreview.show();
    });

    btnPrint.addEventListener('click', function () {
        var w = window.open('', '_blank');
        if (!w) {
            return;
        }
        w.document.open();
        w.document.write(wrapPrintDocument(buildOfficial940Pack()));
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
            var inner = previewMount.querySelector('.f940-doc');
            var html = wrapPrintDocument(inner ? inner.innerHTML : buildOfficial940Pack());
            w.document.open();
            w.document.write(html);
            w.document.close();
            w.focus();
            setTimeout(function () { w.print(); }, 300);
        });
    }

    applyLockedUi(true);
    recalc();
})();
</script>
