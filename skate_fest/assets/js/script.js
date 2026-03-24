// assets/js/script.js

// Carregar dados ao iniciar
document.addEventListener('DOMContentLoaded', function() {
    carregarRanking();
    carregarListaSkatistas();
    
    // Evento do formulário
    document.getElementById('formSkater').addEventListener('submit', cadastrarSkatista);
    
    // Evento do botão resetar
    document.getElementById('limparTodos').addEventListener('click', resetarCompeticao);
});

// Função para cadastrar skatista
function cadastrarSkatista(event) {
    event.preventDefault();
    
    const dados = {
        nome: document.getElementById('nome').value,
        pais: document.getElementById('pais').value,
        idade: parseInt(document.getElementById('idade').value),
        kickflip: parseFloat(document.getElementById('manobra1').value),
        heelflip: parseFloat(document.getElementById('manobra2').value),
        tre_flip: parseFloat(document.getElementById('manobra3').value),
        varial: parseFloat(document.getElementById('manobra4').value),
        laser: parseFloat(document.getElementById('manobra5').value)
    };
    
    fetch('api/cadastrar.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dados)
    })
    .then(response => response.json())
    .then(data => {
        const mensagemDiv = document.getElementById('mensagem');
        if(data.success) {
            mensagemDiv.className = 'mensagem sucesso';
            mensagemDiv.textContent = data.message;
            document.getElementById('formSkater').reset();
            
            // Recarregar ranking e lista
            carregarRanking();
            carregarListaSkatistas();
            
            // Limpar mensagem após 3 segundos
            setTimeout(() => {
                mensagemDiv.textContent = '';
                mensagemDiv.className = 'mensagem';
            }, 3000);
        } else {
            mensagemDiv.className = 'mensagem erro';
            if(data.errors) {
                mensagemDiv.textContent = data.errors.join(', ');
            } else {
                mensagemDiv.textContent = data.message;
            }
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        const mensagemDiv = document.getElementById('mensagem');
        mensagemDiv.className = 'mensagem erro';
        mensagemDiv.textContent = 'Erro ao conectar com o servidor';
    });
}

// Função para carregar ranking (podium)
function carregarRanking() {
    fetch('api/ranking.php?tipo=podium')
    .then(response => response.json())
    .then(data => {
        if(data.success && data.data.length > 0) {
            // Atualizar estatísticas
            if(data.estatisticas) {
                document.getElementById('totalParticipantes').textContent = 
                    data.estatisticas.total_participantes || 0;
            }
            
            // Atualizar podium
            const podiumPositions = ['podium1', 'podium2', 'podium3'];
            podiumPositions.forEach(pos => {
                const element = document.getElementById(pos);
                const infoDiv = element.querySelector('.podium-info');
                if(infoDiv) infoDiv.innerHTML = '';
            });
            
            data.data.forEach((skater, index) => {
                let podiumId = '';
                if(skater.posicao === 1) podiumId = 'podium1';
                else if(skater.posicao === 2) podiumId = 'podium2';
                else if(skater.posicao === 3) podiumId = 'podium3';
                
                if(podiumId) {
                    const element = document.getElementById(podiumId);
                    const infoDiv = element.querySelector('.podium-info');
                    if(infoDiv) {
                        infoDiv.innerHTML = `
                            <strong>${skater.nome}</strong><br>
                            ${skater.pais}<br>
                            Média: ${skater.media_geral}
                        `;
                    }
                }
            });
        } else {
            // Limpar podium se não houver dados
            document.getElementById('totalParticipantes').textContent = '0';
            const podiumInfos = document.querySelectorAll('.podium-info');
            podiumInfos.forEach(info => {
                info.innerHTML = '<em>Vago</em>';
            });
        }
    })
    .catch(error => console.error('Erro ao carregar ranking:', error));
}

// Função para carregar lista completa de skatistas
function carregarListaSkatistas() {
    fetch('api/ranking.php?tipo=completo')
    .then(response => response.json())
    .then(data => {
        const listaDiv = document.getElementById('listaSkaters');
        
        if(data.success && data.data.length > 0) {
            let html = '';
            data.data.forEach(skater => {
                html += `
                    <div class="skater-card">
                        <div class="skater-posicao">${skater.posicao}º</div>
                        <div class="skater-info">
                            <strong>${skater.nome}</strong> (${skater.pais}) - ${skater.idade} anos
                            <div class="skater-notas">
                                K:${skater.kickflip} | H:${skater.heelflip} | T:${skater.tre_flip} | V:${skater.varial} | L:${skater.laser}
                            </div>
                            <div class="skater-media">
                                Média: ${skater.media_geral} | Total: ${skater.pontuacao_total}
                            </div>
                        </div>
                    </div>
                `;
            });
            listaDiv.innerHTML = html;
        } else {
            listaDiv.innerHTML = '<div class="sem-dados">Nenhum skatista cadastrado ainda.</div>';
        }
    })
    .catch(error => {
        console.error('Erro ao carregar lista:', error);
        document.getElementById('listaSkaters').innerHTML = 
            '<div class="erro">Erro ao carregar dados</div>';
    });
}

// Função para resetar competição
function resetarCompeticao() {
    if(confirm('Tem certeza que deseja resetar toda a competição? Esta ação não pode ser desfeita!')) {
        fetch('api/resetar.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                carregarRanking();
                carregarListaSkatistas();
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao resetar competição');
        });
    }
}