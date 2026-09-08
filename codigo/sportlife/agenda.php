<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportLife - Horários</title>
    <style>
        :root {
            --primary-purple: #4f46e5;
            --deep-purple: #1e1b4b;
            --neon-glow: #818cf8;
            --bg-black: #0a0a0f;
            --card-gray: rgba(255, 255, 255, 0.05);
            --border: rgba(79, 70, 229, 0.25); 
            --text-muted: #94a3b8;
            --white: #f8fafc;
        }

        body {
            margin: 0;
            font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-black);
            color: var(--white);
            line-height: 1.6;
        }

        /* HEADER */
        .header {
            padding: 40px 20px;
            background: linear-gradient(180deg, var(--deep-purple) 0%, var(--bg-black) 100%);
            text-align: center;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--neon-glow);
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }

        /* FILTROS */
        .filters {
            display: flex;
            gap: 20px;
            padding: 30px 20px;
            flex-wrap: wrap;
            justify-content: center;
            background: rgba(15, 23, 42, 0.5);
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-select {
            background: var(--card-gray);
            padding: 12px 20px;
            border-radius: 12px;
            color: var(--white);
            min-width: 220px;
            border: 1px solid var(--border);
            cursor: pointer;
            outline: none;
            transition: all 0.3s ease;
            appearance: none; /* Remove seta padrão */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23818cf8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
        
            background: #28283a;
            color: white;
        
        }

        .filter-select:hover {
            border-color: var(--neon-glow);
            background-color: rgb(#28283a);
        }

        /* INFO BOX */
        .info-box {
            margin: 0 auto 30px;
            max-width: 800px;
            padding: 20px;
            border: 1px solid var(--border);
            border-radius: 16px;
            text-align: center;
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.05), rgba(129, 140, 248, 0.05));
            backdrop-filter: blur(10px);
        }

        .info-box strong {
            color: var(--neon-glow);
        }

        /* GRID HORÁRIOS */
        .grid-container {
            overflow-x: auto;
            padding: 0 20px 50px;
        }

        .grid {
            display: grid;
            grid-template-columns: 100px repeat(7, 1fr);
            gap: 12px;
            min-width: 1000px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .day {
            text-align: center;
            font-weight: 700;
            padding: 15px;
            background: var(--deep-purple);
            border-radius: 10px;
            color: var(--neon-glow);
            font-size: 14px;
            border: 1px solid var(--border);
        }

        .time {
            color: var(--text-muted);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        /* AULAS */
        .class {
            background: var(--card-gray);
            border: 1px solid var(--border);
            padding: 15px;
            border-radius: 12px;
            font-size: 13px;
            min-height: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .class:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.4);
        }

        .class.active {
            background: rgba(79, 70, 229, 0.15);
            border-left: 4px solid var(--primary-purple);
        }

        .class.highlight {
            background: rgba(129, 140, 248, 0.2);
            border-left: 4px solid var(--neon-glow);
            box-shadow: inset 0 0 15px rgba(129, 140, 248, 0.1);
        }

        .class-name {
            font-weight: 700;
            color: var(--white);
            margin-bottom: 4px;
        }

        .instructor {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 400;
        }

        .empty {
            opacity: 0.3;
            border: 1px dashed var(--border);
            background: transparent;
        }

    </style>
</head>

<body>

<div class="header">
    SportLife <span style="font-weight: 300; color: var(--text-muted);">| Schedule</span>
</div>

<div class="filters">
    <div class="filter-group">
        <select class="filter-select">
            <option value="curitiba">Unidade: Curitiba</option>
            <option value="pinhais">Unidade: Pinhais</option>
            <option value="batel">Unidade: Batel</option>
        </select>
    </div>
    
    <div class="filter-group">
        <select class="filter-select">
            <option value="todas">Modalidade: Todas</option>
            <option value="musculacao">Musculação</option>
            <option value="spinning">Spinning</option>
            <option value="cross">Cross Training</option>
            <option value="yoga">Yoga / Pilates</option>
        </select>
    </div>

    <div class="filter-group">
        <select class="filter-select">
            <option value="adulto">Nível: Adulto</option>
        </select>
    </div>
</div>

<div class="info-box">
    <strong>Horário de Funcionamento:</strong><br>
    Seg a Sex: 05h às 23h • <strong>Sáb: 08h às 16h</strong> 
</div>

<div class="grid-container">
    <div class="grid">
        <div></div>
        <div class="day">SEG</div>
        <div class="day">TER</div>
        <div class="day">QUA</div>
        <div class="day">QUI</div>
        <div class="day">SEX</div>
        <div class="day">SAB</div>
        <div class="day">DOM</div>

        <div class="time">06:00</div>
        <div class="class active"><span class="class-name">GAP</span> <span class="instructor">Prof. Ana</span></div>
        <div class="class highlight"><span class="class-name">Spinning</span> <span class="instructor">Prof. Leo</span></div>
        <div class="class active"><span class="class-name">Yoga</span> <span class="instructor">Prof. Bia</span></div>
        <div class="class active"><span class="class-name">GAP</span> <span class="instructor">Prof. Ana</span></div>
        <div class="class highlight"><span class="class-name">Cross</span> <span class="instructor">Prof. Caio</span></div>
        <div class="class empty">--</div>
        <div class="class empty">--</div>

        <div class="time">08:00</div>
        <div class="class highlight"><span class="class-name">Cross</span> <span class="instructor">Prof. Caio</span></div>
        <div class="class active"><span class="class-name">Pilates</span> <span class="instructor">Prof. Mel</span></div>
        <div class="class highlight"><span class="class-name">Spinning</span> <span class="instructor">Prof. Leo</span></div>
        <div class="class active"><span class="class-name">Zumba</span> <span class="instructor">Prof. Dani</span></div>
        <div class="class highlight"><span class="class-name">Cross</span> <span class="instructor">Prof. Caio</span></div>
        <div class="class highlight"><span class="class-name">Spinning</span> <span class="instructor">Prof. Igor</span></div>
        <div class="class active"><span class="class-name">Yoga</span> <span class="instructor">Prof. Bia</span></div>

        <div class="time">10:00</div>
        <div class="class active"><span class="class-name">Musculação</span> <span class="instructor">Monitoria</span></div>
        <div class="class active"><span class="class-name">Funcional</span> <span class="instructor">Prof. Jhow</span></div>
        <div class="class active"><span class="class-name">Musculação</span> <span class="instructor">Monitoria</span></div>
        <div class="class active"><span class="class-name">Ritmos</span> <span class="instructor">Prof. Dani</span></div>
        <div class="class active"><span class="class-name">Musculação</span> <span class="instructor">Monitoria</span></div>
        <div class="class highlight"><span class="class-name">Cross</span> <span class="instructor">Prof. Caio</span></div>
        <div class="class active"><span class="class-name">Zumba</span> <span class="instructor">Prof. Dani</span></div>

        <div class="time">18:00</div>
        <div class="class highlight"><span class="class-name">Spinning</span> <span class="instructor">Prof. Igor</span></div>
        <div class="class highlight"><span class="class-name">Cross</span> <span class="instructor">Prof. Caio</span></div>
        <div class="class active"><span class="class-name">Boxe</span> <span class="instructor">Prof. Marc</span></div>
        <div class="class highlight"><span class="class-name">Spinning</span> <span class="instructor">Prof. Igor</span></div>
        <div class="class active"><span class="class-name">Ritmos</span> <span class="instructor">Prof. Dani</span></div>
        <div class="class empty">Fechado</div>
        <div class="class empty">Fechado</div>

        <div class="time">20:00</div>
        <div class="class active"><span class="class-name">Zumba</span> <span class="instructor">Prof. Dani</span></div>
        <div class="class active"><span class="class-name">GAP</span> <span class="instructor">Prof. Ana</span></div>
        <div class="class highlight"><span class="class-name">Cross</span> <span class="instructor">Prof. Caio</span></div>
        <div class="class active"><span class="class-name">Yoga</span> <span class="instructor">Prof. Bia</span></div>
        <div class="class highlight"><span class="class-name">Spinning</span> <span class="instructor">Prof. Igor</span></div>
        <div class="class empty">Fechado</div>
        <div class="class empty">Fechado</div>
    </div>
</div>

</body>
</html>