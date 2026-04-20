<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Статус на предмет</title>
</head>
<body style="font-family: Arial; background:#f5f5f5; padding:20px;">

<div style="background:white; padding:20px; border-radius:10px;">
    <h2 style="color:#333;">📚 Статус на предмет</h2>

    <p><strong>Студент:</strong> {{ $enrollment->student->full_name }}</p>
    <p><strong>Предмет:</strong> {{ $enrollment->subject->name }}</p>

    <p>
        <strong>Статус:</strong>
        <span style="
                color:
                @if($enrollment->status == 'approved') green
                @elseif($enrollment->status == 'rejected') red
                @else orange
                @endif;
            ">
                {{ strtoupper($enrollment->status) }}
            </span>
    </p>

    <p><strong>Оцена:</strong> {{ $enrollment->grade ?? 'N/A' }}</p>

    <hr>

    <p style="font-size:12px; color:gray;">
        Ова е автоматска порака од системот.
    </p>
</div>

</body>
</html>
