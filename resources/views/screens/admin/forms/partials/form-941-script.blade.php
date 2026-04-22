<script>
(function () {
    if (typeof bootstrap === 'undefined') {
        return;
    }

    var taxYear = window.__F941_TAX_YEAR || new Date().getFullYear();
    var btnOverride = document.getElementById('f941BtnOverride');
    var btnPreview = document.getElementById('f941BtnPreview');
    var btnPreparer = document.getElementById('f941BtnPreparer');
    var overrideModalEl = document.getElementById('f941OverrideModal');
    var preparerModalEl = document.getElementById('f941PreparerModal');
    var previewModalEl = document.getElementById('f941PreviewModal');
    var previewMount = document.getElementById('f941PreviewMount');
    var previewPrintBtn = document.getElementById('f941PreviewPrint');
    var ack = document.getElementById('f941OverrideAck');
    var okBtn = document.getElementById('f941OverrideOk');

    if (!overrideModalEl) {
        return;
    }

    var bsOverride = bootstrap.Modal.getOrCreateInstance(overrideModalEl);
    var bsPreparer = preparerModalEl ? bootstrap.Modal.getOrCreateInstance(preparerModalEl) : null;
    var bsPreview = previewModalEl ? bootstrap.Modal.getOrCreateInstance(previewModalEl) : null;

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
        if (s.length > 14) {
            s = s.slice(0, 14);
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

    function round2(n) {
        return Math.round(n * 100) / 100;
    }

    function recalc941() {
        var l1 = parseInt(String(val('f941-l1')).replace(/\D/g, ''), 10);
        if (isNaN(l1) || l1 < 0) {
            l1 = 0;
        }
        if (l1 > 9999999) {
            l1 = 9999999;
        }
        setVal('f941-l1', String(l1));

        var l2 = parseMoney(val('f941-l2'));
        var l3 = parseMoney(val('f941-l3'));
        var l5a1 = parseMoney(val('f941-l5a1'));
        var l5b1 = parseMoney(val('f941-l5b1'));
        var l5c1 = parseMoney(val('f941-l5c1'));
        var l5d1 = parseMoney(val('f941-l5d1'));
        var l5f = parseMoney(val('f941-l5f'));
        var l7 = parseMoney(val('f941-l7'));
        var l8 = parseMoney(val('f941-l8'));
        var l9 = parseMoney(val('f941-l9'));
        var l11 = parseMoney(val('f941-l11'));
        var l13 = parseMoney(val('f941-l13'));

        var l5a2 = round2(l5a1 * 0.124);
        var l5b2 = round2(l5b1 * 0.124);
        var l5c2 = round2(l5c1 * 0.029);
        var l5d2 = round2(l5d1 * 0.009);
        var l5e = round2(l5a2 + l5b2 + l5c2 + l5d2);

        setVal('f941-l5a2', formatMoney(l5a2));
        setVal('f941-l5b2', formatMoney(l5b2));
        setVal('f941-l5c2', formatMoney(l5c2));
        setVal('f941-l5d2', formatMoney(l5d2));
        setVal('f941-l5e', formatMoney(l5e));

        var l6 = round2(l3 + l5e + l5f);
        setVal('f941-l6', formatMoney(l6));

        var l10 = round2(l6 + l7 + l8 + l9);
        setVal('f941-l10', formatMoney(l10));

        var l12 = round2(l10 - l11);
        setVal('f941-l12', formatMoney(l12));

        if (l12 > l13) {
            setVal('f941-l14', formatMoney(l12 - l13));
            setVal('f941-l15a', '');
        } else if (l13 > l12) {
            setVal('f941-l14', '0.00');
            setVal('f941-l15a', formatMoney(l13 - l12));
        } else {
            setVal('f941-l14', '0.00');
            setVal('f941-l15a', '');
        }

        var l16a = document.getElementById('f941-l16a');
        var l16b = document.getElementById('f941-l16b');
        var l16c = document.getElementById('f941-l16c');
        if (l16a && l16b && editingEnabled && (!l16c || !l16c.checked)) {
            if (l12 < 2500) {
                l16a.checked = true;
                l16b.checked = false;
            } else {
                l16a.checked = false;
                l16b.checked = true;
            }
        }

        var m1 = parseMoney(val('f941-m1'));
        var m2 = parseMoney(val('f941-m2'));
        var m3 = parseMoney(val('f941-m3'));
        var msum = m1 + m2 + m3;
        setVal('f941-mtot', msum > 0 ? formatMoney(msum) : '');
    }

    function applyLockedUi(locked) {
        document.querySelectorAll('.f941-num, .f941-txt').forEach(function (el) {
            if (el.classList.contains('f941-no-override')) {
                el.readOnly = true;
                return;
            }
            el.readOnly = locked;
            el.classList.toggle('bg-light', locked);
        });
        document.querySelectorAll('.f941-cb').forEach(function (el) {
            el.disabled = locked;
        });
    }

    function setEditing(on) {
        editingEnabled = on;
        applyLockedUi(!on);
        if (btnOverride) {
            btnOverride.textContent = on ? 'Enable calculations again' : 'Override calculations';
        }
    }

    var derivedLineIds = [
        'f941-l5a2', 'f941-l5b2', 'f941-l5c2', 'f941-l5d2', 'f941-l5e', 'f941-l6', 'f941-l10', 'f941-l12',
        'f941-l14', 'f941-l15a', 'f941-mtot',
    ];

    document.querySelectorAll('.f941-num').forEach(function (el) {
        el.addEventListener('input', function () {
            if (!editingEnabled) {
                return;
            }
            if (el.id === 'f941-l1') {
                el.value = String(el.value || '').replace(/\D/g, '').slice(0, 7);
                return;
            }
            el.value = sanitizeMoney(el.value);
        });
        el.addEventListener('blur', function () {
            if (!editingEnabled) {
                return;
            }
            if (el.id === 'f941-l1') {
                var n = parseInt(String(el.value || '').replace(/\D/g, ''), 10) || 0;
                el.value = String(n);
                recalc941();
                return;
            }
            el.value = formatMoney(parseMoney(el.value));
            if (derivedLineIds.indexOf(el.id) === -1) {
                recalc941();
            }
        });
    });

    if (btnOverride) {
        btnOverride.addEventListener('click', function () {
            if (editingEnabled) {
                recalc941();
                setEditing(false);
                return;
            }
            if (ack) {
                ack.checked = false;
            }
            if (okBtn) {
                okBtn.disabled = true;
            }
            bsOverride.show();
        });
    }

    if (ack && okBtn) {
        ack.addEventListener('change', function () {
            okBtn.disabled = !ack.checked;
        });
        okBtn.addEventListener('click', function () {
            bsOverride.hide();
            setEditing(true);
            recalc941();
        });
    }

    overrideModalEl.addEventListener('hidden.bs.modal', function () {
        if (ack) {
            ack.checked = false;
        }
        if (okBtn) {
            okBtn.disabled = true;
        }
    });

    if (btnPreparer && bsPreparer) {
        btnPreparer.addEventListener('click', function () {
            bsPreparer.show();
        });
    }

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function chk(c, id) {
        return c[id] ? 'X' : '\u00a0';
    }

    function lv(lines, id) {
        return escapeHtml(lines[id] == null ? '' : String(lines[id]));
    }

    function formatEinDisp(ein) {
        var d = String(ein || '').replace(/\D/g, '');
        if (d.length === 9) {
            return d.slice(0, 2) + '-' + d.slice(2);
        }
        return String(ein || '');
    }

    function collect941Snapshot() {
        recalc941();
        var lines = {};
        document.querySelectorAll('input[id^="f941-"], textarea[id^="f941-"]').forEach(function (el) {
            if (!el.id || el.type === 'checkbox' || el.type === 'radio') {
                return;
            }
            lines[el.id] = el.value;
        });
        var checks = {};
        document.querySelectorAll('.f941-cb').forEach(function (el) {
            if (el.id) {
                checks[el.id] = !!el.checked;
            }
        });
        var emp = window.__F941_EMPLOYER || {};
        return { year: taxYear, lines: lines, checks: checks, emp: emp };
    }

    function pageShell(inner) {
        return '<div class="f941-print-page">' + inner + '</div>';
    }

    function watermark() {
        return '<div class="f941-wm">Not Updated — Do NOT File</div>';
    }

    function build941PreviewPack() {
        var s = collect941Snapshot();
        var L = s.lines;
        var c = s.checks;
        var e = s.emp || {};
        var y = s.year;
        var semi = !!c['f941-l16c'];
        var under = !!c['f941-l16a'] && !semi;
        var monthly = !!c['f941-l16b'] && !semi;
        var inner = ''
            + watermark()
            + '<div class="f941-inner">'
            + '<div class="f941-repeat-name">'
            + '<table class="f941-pdf-mini"><tr><td class="f941-pdf-k">Name (not your trade name)</td><td class="f941-pdf-v">' + escapeHtml(e.legal_name || '') + '</td></tr>'
            + '<tr><td class="f941-pdf-k">Employer identification number (EIN)</td><td class="f941-pdf-v">' + escapeHtml(formatEinDisp(e.ein)) + '</td></tr></table>'
            + '</div>'

            + '<div class="f941-pdf-part">Part 1 (continued): Deposits, balance due, overpayment</div>'
            + '<table class="f941-pdf-lines">'
            + '<tr><td class="f941-pdf-ln">12</td><td class="f941-pdf-desc">Total taxes after adjustments and nonrefundable credits. Subtract line 11 from line 10</td><td class="f941-pdf-amt">' + lv(L, 'f941-l12') + '</td></tr>'
            + '<tr><td class="f941-pdf-ln">13</td><td class="f941-pdf-desc">Total deposits for this quarter, including overpayment applied from a prior quarter and overpayments applied from Form 941-X, 941-X (PR), or 944-X filed in the current quarter</td><td class="f941-pdf-amt">' + lv(L, 'f941-l13') + '</td></tr>'
            + '<tr><td class="f941-pdf-ln">14</td><td class="f941-pdf-desc">Balance due. If line 12 is more than line 13, enter the difference</td><td class="f941-pdf-amt">' + lv(L, 'f941-l14') + '</td></tr>'
            + '<tr><td class="f941-pdf-ln">15a</td><td class="f941-pdf-desc">Overpayment. If line 13 is more than line 12, enter the difference</td><td class="f941-pdf-amt">' + lv(L, 'f941-l15a') + '</td></tr>'
            + '</table>'
            + '<div class="f941-pdf-inline"><span class="f941-cbx">' + chk(c, 'f941-l15b-next') + '</span> <strong>15b</strong> Apply to next return'
            + ' &nbsp;&nbsp; <span class="f941-cbx">' + chk(c, 'f941-l15b-refund') + '</span> Send a refund</div>'
            + '<table class="f941-pdf-lines" style="margin-top:6px;"><tr><td class="f941-pdf-ln">15c</td><td class="f941-pdf-desc">Routing number</td><td class="f941-pdf-txt">' + lv(L, 'f941-l15c') + '</td></tr></table>'
            + '<div class="f941-pdf-inline" style="margin-top:4px;"><strong>15d</strong> Account type '
            + '<span class="f941-cbx">' + chk(c, 'f941-l15d-chk') + '</span> Checking '
            + '<span class="f941-cbx">' + chk(c, 'f941-l15d-sav') + '</span> Savings</div>'
            + '<table class="f941-pdf-lines"><tr><td class="f941-pdf-ln">15e</td><td class="f941-pdf-desc">Account number</td><td class="f941-pdf-txt">' + lv(L, 'f941-l15e') + '</td></tr></table>'

            + '<div class="f941-pdf-part">Part 2: Tell us about your deposit schedule and tax liability for this quarter.</div>'
            + '<p class="f941-pdf-note">If you&apos;re unsure about whether you&apos;re a monthly schedule depositor or a semiweekly schedule depositor, see section 11 of Pub. 15.</p>'
            + '<table class="f941-pdf-lines"><tr><td colspan="2">'
            + '<div class="f941-pdf-inline"><span class="f941-cbx">' + (under ? 'X' : '\u00a0') + '</span> <strong>16</strong> Line 12 on this return is less than $2,500 or line 12 on the return for the prior quarter was less than $2,500, and you didn&apos;t incur a $100,000 next-day deposit obligation during the current quarter. If line 12 for the prior quarter was less than $2,500 but line 12 on this return is $100,000 or more, you must provide a record of your federal tax liability. If you&apos;re a monthly schedule depositor, complete the deposit schedule below; if you&apos;re a semiweekly schedule depositor, attach Schedule B (Form 941). Go to Part 3.</div>'
            + '<div class="f941-pdf-inline"><span class="f941-cbx">' + (monthly ? 'X' : '\u00a0') + '</span> You were a monthly schedule depositor for the entire quarter. Enter your tax liability for each month and total liability for the quarter, then go to Part 3.</div>'
            + '</td></tr></table>'
            + '<p class="f941-pdf-note" style="margin:4px 0 2px;">Tax liability:</p>'
            + '<table class="f941-pdf-lines" style="margin-top:0;">'
            + '<tr><td class="f941-pdf-desc">Month 1</td><td class="f941-pdf-amt">' + lv(L, 'f941-m1') + '</td></tr>'
            + '<tr><td class="f941-pdf-desc">Month 2</td><td class="f941-pdf-amt">' + lv(L, 'f941-m2') + '</td></tr>'
            + '<tr><td class="f941-pdf-desc">Month 3</td><td class="f941-pdf-amt">' + lv(L, 'f941-m3') + '</td></tr>'
            + '<tr><td class="f941-pdf-desc"><strong>Total liability for quarter</strong></td><td class="f941-pdf-amt">' + lv(L, 'f941-mtot') + '</td></tr>'
            + '</table>'
            + '<p class="f941-pdf-note" style="margin-top:2px;">Total must equal line 12.</p>'
            + '<table class="f941-pdf-lines" style="margin-top:6px;"><tr><td colspan="2">'
            + '<div class="f941-pdf-inline"><span class="f941-cbx">' + (semi ? 'X' : '\u00a0') + '</span> You were a semiweekly schedule depositor for any part of this quarter. Complete Schedule B (Form 941), Report of Tax Liability for Semiweekly Schedule Depositors, and attach it to Form 941. Go to Part 3.</div>'
            + '</td></tr></table>'

            + '<div class="f941-pdf-part">Part 3: Tell us about your business. If a question does NOT apply to your business, leave it blank.</div>'
            + '<div class="f941-pdf-inline"><span class="f941-cbx">' + chk(c, 'f941-l17') + '</span> <strong>17</strong> If your business has closed or you stopped paying wages, check here and enter the final date you paid wages; also attach a statement to your return. See instructions.</div>'
            + '<table class="f941-pdf-lines"><tr><td class="f941-pdf-desc">Final date wages paid</td><td class="f941-pdf-txt">' + lv(L, 'f941-l17d') + '</td></tr></table>'
            + '<div class="f941-pdf-inline" style="margin-top:4px;"><span class="f941-cbx">' + chk(c, 'f941-l18') + '</span> <strong>18</strong> If you&apos;re a seasonal employer and you don&apos;t have to file a return for every quarter of the year, check here.</div>'

            + '<div class="f941-pdf-part">Part 4: May we speak with your third-party designee?</div>'
            + '<p class="f941-pdf-note">Do you want to allow an employee, a paid tax preparer, or another person to discuss this return with the IRS? See the instructions for details.</p>'
            + '<div class="f941-pdf-inline"><span class="f941-cbx">' + chk(c, 'f941-l19y') + '</span> Yes. Designee&apos;s name and phone number<br><span class="f941-pdf-note" style="margin:0;">Select a 5-digit personal identification number (PIN) to use when talking to the IRS.</span></div>'
            + '<div class="f941-pdf-inline"><span class="f941-cbx">' + chk(c, 'f941-l19n') + '</span> No.</div>'

            + '<div class="f941-pdf-part">Part 5: Sign here. You MUST complete both pages of Form 941 and SIGN it.</div>'
            + '<p class="f941-pdf-note">Under penalties of perjury, I declare that I have examined this return, including accompanying schedules and statements, and to the best of my knowledge and belief, it is true, correct, and complete. Declaration of preparer (other than taxpayer) is based on all information of which preparer has any knowledge.</p>'
            + '<p class="f941-pdf-note">Signature, date, title, paid preparer, and daytime phone are completed on the official paper form or through your tax professional.</p>'
            + '</div>';
        return pageShell(inner);
    }

    var printCss941 = ''
        + '<style>'
        + '*{box-sizing:border-box}'
        + '.f941-print-root{font-family:system-ui,sans-serif;padding:12px;background:#e9e9e9;min-height:100%}'
        + '.f941-doc{font-family:"Times New Roman",Times,serif;font-size:9.5pt;color:#000}'
        + '.f941-print-page{position:relative;width:8.5in;min-height:10.5in;margin:0 auto 16px;padding:0.4in 0.45in 0.4in;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,.2);page-break-after:always}'
        + '.f941-print-page:last-child{page-break-after:auto;margin-bottom:0}'
        + '.f941-inner{position:relative;z-index:2}'
        + '.f941-wm{position:absolute;left:0;right:0;top:34%;text-align:center;font-size:28pt;font-weight:bold;color:#bbb;opacity:0.38;transform:rotate(-18deg);pointer-events:none;z-index:1;white-space:nowrap}'
        + '.f941-repeat-name{margin-bottom:8pt;padding-bottom:6pt;border-bottom:1px solid #222}'
        + '.f941-pdf-mini{width:100%;border-collapse:collapse;font-size:8.5pt}'
        + '.f941-pdf-mini td{padding:2pt 0;vertical-align:top}'
        + '.f941-pdf-k{font-weight:bold;width:42%}'
        + '.f941-pdf-v{border-bottom:0.35pt solid #999}'
        + '.f941-pdf-part{font-weight:bold;font-size:10pt;margin:10px 0 4px;border-bottom:1px solid #000;padding-bottom:2px}'
        + '.f941-pdf-lines{width:100%;border-collapse:collapse;margin:2px 0 6px;font-size:8.5pt}'
        + '.f941-pdf-lines td{border:1px solid #333;padding:3px 5px;vertical-align:middle}'
        + '.f941-pdf-ln{width:6%;text-align:center;font-weight:bold;background:#f5f5f5}'
        + '.f941-pdf-desc{width:64%}'
        + '.f941-pdf-amt{width:14%;text-align:right;font-variant-numeric:tabular-nums}'
        + '.f941-pdf-txt{text-align:left}'
        + '.f941-pdf-inline{margin:3px 0;font-size:8.5pt;line-height:1.35}'
        + '.f941-pdf-note{font-size:7.5pt;color:#333;margin:4px 0 6px}'
        + '.f941-cbx{border:1px solid #000;width:1.35rem;height:1.15rem;display:inline-flex;align-items:center;justify-content:center;font-weight:bold;flex-shrink:0;margin-right:4px;vertical-align:middle}'
        + '@media print{'
        + '.f941-print-root{background:#fff;padding:0}'
        + '.f941-print-page{box-shadow:none;margin:0;page-break-after:always}'
        + '.f941-print-page:last-child{page-break-after:auto}'
        + '}'
        + '</style>';

    function wrap941PrintDocument(innerBody) {
        return '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Form 941 — ' + escapeHtml(String(taxYear)) + '</title>'
            + printCss941
            + '</head><body><div class="f941-print-root"><div class="f941-doc">' + innerBody + '</div></div></body></html>';
    }

    if (btnPreview && previewMount && bsPreview) {
        btnPreview.addEventListener('click', function () {
            previewMount.innerHTML = '<div class="f941-print-root">' + printCss941 + '<div class="f941-doc">' + build941PreviewPack() + '</div></div>';
            bsPreview.show();
        });
    }

    if (previewPrintBtn) {
        previewPrintBtn.addEventListener('click', function () {
            var w = window.open('', '_blank');
            if (!w) {
                return;
            }
            var inner = previewMount ? previewMount.querySelector('.f941-doc') : null;
            var html = wrap941PrintDocument(inner ? inner.innerHTML : build941PreviewPack());
            w.document.open();
            w.document.write(html);
            w.document.close();
            w.focus();
            setTimeout(function () { w.print(); }, 300);
        });
    }

    applyLockedUi(true);
    recalc941();
})();
</script>
