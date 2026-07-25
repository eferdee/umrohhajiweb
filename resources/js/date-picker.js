// Komponen date picker ringan berbasis Alpine.js.
// Dipakai lewat <x-site.date-field> — mendukung dropdown bulan & tahun
// supaya navigasi ke tanggal lama (mis. tahun 1900-an) tetap cepat,
// tanpa bergantung pada date picker bawaan browser.

const MONTH_NAMES = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
];
const DAY_NAMES = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

function parseISO(iso) {
    if (!iso || typeof iso !== 'string') return null;
    const parts = iso.split('-').map(Number);
    if (parts.length !== 3 || parts.some((n) => Number.isNaN(n))) return null;
    const [y, m, d] = parts;
    if (!y || !m || !d) return null;
    return new Date(y, m - 1, d);
}

function toISO(date) {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
}

export default function sitePicker(get, set, opts = {}) {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const minDate = opts.minDate ? parseISO(opts.minDate) : null;
    const maxDate = opts.maxDate ? parseISO(opts.maxDate) : null;
    const minYear = opts.minYear || 1900;
    const maxYear = opts.maxYear || (today.getFullYear() + 1);
    const fallback = opts.defaultYear
        ? new Date(opts.defaultYear, 0, 1)
        : new Date(today.getFullYear(), today.getMonth(), 1);

    return {
        open: false,
        viewYear: (parseISO(get()) || fallback).getFullYear(),
        viewMonth: (parseISO(get()) || fallback).getMonth(),
        monthNames: MONTH_NAMES,
        dayNames: DAY_NAMES,

        get displayValue() {
            const d = parseISO(get());
            if (!d) return '';
            return `${d.getDate()} ${MONTH_NAMES[d.getMonth()]} ${d.getFullYear()}`;
        },
        get years() {
            const arr = [];
            for (let y = maxYear; y >= minYear; y--) arr.push(y);
            return arr;
        },
        get leadingBlanks() {
            return new Date(this.viewYear, this.viewMonth, 1).getDay();
        },
        get daysGrid() {
            const total = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();
            return Array.from({ length: total }, (_, i) => i + 1);
        },

        toggle() {
            this.open = !this.open;
            if (this.open) {
                const d = parseISO(get()) || fallback;
                this.viewYear = d.getFullYear();
                this.viewMonth = d.getMonth();
            }
        },
        close() {
            this.open = false;
        },
        prevMonth() {
            this.viewMonth--;
            if (this.viewMonth < 0) {
                this.viewMonth = 11;
                this.viewYear--;
            }
        },
        nextMonth() {
            this.viewMonth++;
            if (this.viewMonth > 11) {
                this.viewMonth = 0;
                this.viewYear++;
            }
        },
        goToday() {
            this.viewYear = today.getFullYear();
            this.viewMonth = today.getMonth();
        },
        isDisabled(day) {
            const d = new Date(this.viewYear, this.viewMonth, day);
            if (minDate && d < minDate) return true;
            if (maxDate && d > maxDate) return true;
            return false;
        },
        isSelected(day) {
            const d = parseISO(get());
            return !!d && d.getFullYear() === this.viewYear && d.getMonth() === this.viewMonth && d.getDate() === day;
        },
        isToday(day) {
            return today.getFullYear() === this.viewYear && today.getMonth() === this.viewMonth && today.getDate() === day;
        },
        select(day) {
            if (this.isDisabled(day)) return;
            set(toISO(new Date(this.viewYear, this.viewMonth, day)));
            this.open = false;
        },
        clear() {
            set('');
            this.open = false;
        },
    };
}
