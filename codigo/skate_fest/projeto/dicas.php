<?php
require_once 'config.php';

$dicas = [
    ['categoria'=>'Manobras','titulo'=>'Como dar seu primeiro Ollie','conteudo'=>'O ollie é a base de todas as manobras. Posicione os pés corretamente e dê o estalo.'],
    ['categoria'=>'Equipamento','titulo'=>'Como escolher o shape ideal','conteudo'=>'Shapes mais largos (8.0"-8.25") oferecem estabilidade para iniciantes.'],
    ['categoria'=>'Cuidados','titulo'=>'Manutenção do skate','conteudo'=>'Lubrifique os rolamentos a cada 15 dias e verifique os parafusos.'],
    ['categoria'=>'Manobras','titulo'=>'Como treinar kickflip','conteudo'=>'Posicione o pé dianteiro inclinado e coordene o estalo com o movimento.'],
    ['categoria'=>'Treino','titulo'=>'Melhorar equilíbrio','conteudo'=>'Pratique ficar parado em cima do skate por 5 minutos por dia.'],
    ['categoria'=>'Cuidados','titulo'=>'Limpar rolamentos','conteudo'=>'Remova, limpe com desengraxante e lubrifique. Nunca use WD-40!'],
    ['categoria'=>'Manobras','titulo'=>'Pop Shove-It','conteudo'=>'Pé traseiro na ponta, dianteiro no meio. Estalo e gire 180°.'],
    ['categoria'=>'Equipamento','titulo'=>'Tamanho de roda','conteudo'=>'Menores (50-53mm) para street, maiores (54-60mm) para rampas.']
];
?>
<?php include 'header.php'; ?>

<main class="container">
    <section class="hero">
        <h1>💡 DICAS DE SKATE</h1>
        <p>Aprenda e evolua seu estilo</p>
    </section>
    
    <div class="dicas-grid">
        <?php foreach($dicas as $d): ?>
            <div class="dica-card">
                <span class="dica-categoria"><?= htmlspecialchars($d['categoria']) ?></span>
                <h3><?= htmlspecialchars($d['titulo']) ?></h3>
                <p><?= htmlspecialchars($d['conteudo']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'footer.php'; ?>