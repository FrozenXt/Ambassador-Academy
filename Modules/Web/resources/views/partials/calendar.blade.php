@php
    // Dynamic calendar, driven by ?month= & ?year= (falls back to current month)
    $calMonth = \Carbon\Carbon::create(request('year', now()->year), request('month', now()->month), 1);

    $prevMonth = $calMonth->copy()->subMonth();
    $nextMonth = $calMonth->copy()->addMonth();

    // Colour palette cycled per event, same as the featured tags
    $calColors = ['ev-maroon', 'ev-green', 'ev-gold'];

    // Map day-of-month => colour class, for events falling in the displayed month
    $eventDayMap = [];
    $colorIndex = 0;
    foreach ($calendarEvents ?? ($upcomingEvents ?? []) as $ev) {
        $d = \Carbon\Carbon::parse($ev->start_date);
        if ($d->month === $calMonth->month && $d->year === $calMonth->year) {
            $eventDayMap[$d->day] = $calColors[$colorIndex % count($calColors)];
            $colorIndex++;
        }
    }

    $firstDow = $calMonth->copy()->startOfMonth()->dayOfWeek; // 0=Sun..6=Sat
    $daysInMonth = $calMonth->daysInMonth;
    $daysInPrevMon = $prevMonth->daysInMonth;
    $totalCells = $firstDow + $daysInMonth;
    $trailingDays = (7 - ($totalCells % 7)) % 7;
@endphp

<div class="calendar-head">
    <a href="?month={{ $prevMonth->month }}&year={{ $prevMonth->year }}" class="cal-nav"
        data-month="{{ $prevMonth->month }}" data-year="{{ $prevMonth->year }}" aria-label="Previous month"><i
            class="fa-solid fa-chevron-left"></i></a>
    <h4>{{ $calMonth->format('F Y') }}</h4>
    <a href="?month={{ $nextMonth->month }}&year={{ $nextMonth->year }}" class="cal-nav"
        data-month="{{ $nextMonth->month }}" data-year="{{ $nextMonth->year }}" aria-label="Next month"><i
            class="fa-solid fa-chevron-right"></i></a>
</div>

<div class="calendar-grid">
    <span class="cal-dow">SUN</span><span class="cal-dow">MON</span><span class="cal-dow">TUE</span>
    <span class="cal-dow">WED</span><span class="cal-dow">THU</span><span class="cal-dow">FRI</span><span
        class="cal-dow">SAT</span>

    @for ($i = $firstDow - 1; $i >= 0; $i--)
        <span class="cal-day muted">{{ $daysInPrevMon - $i }}</span>
    @endfor

    @for ($d = 1; $d <= $daysInMonth; $d++)
        @if (isset($eventDayMap[$d]))
            <span class="cal-day has-event {{ $eventDayMap[$d] }}">{{ $d }}</span>
        @else
            <span class="cal-day">{{ $d }}</span>
        @endif
    @endfor

    @for ($d = 1; $d <= $trailingDays; $d++)
        <span class="cal-day muted">{{ $d }}</span>
    @endfor
</div>
