let deck = [];
let playerHand = [];
let aiHand = [];
let discardPile = [];
let currentPlayer = 'player';
let currentColor = '';
let gameActive = false;
let pendingWildCard = null;
let playerCalledUno = false;
let aiCalledUno = false;
let unoTimer = null;
let unoTimeLeft = 0;
let canCatchUno = false;
let playerBalance = 5000;
let currentBet = 0;
const INITIAL_BALANCE = 5000;

const colors = ['red', 'blue', 'green', 'yellow'];
const numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
const actions = ['skip', 'reverse', '+2'];

function getCardImage(card) {
    if (card.value === 'wild') {
        return 'assets/wild.png';
    } else if (card.value === '+4') {
        return 'assets/plus_4.png';
    } else if (card.value === '+2') {
        return `assets/${card.color}_plus2.png`;
    } else if (card.value === 'skip') {
        return `assets/${card.color}_skip.png`;
    } else if (card.value === 'reverse') {
        return `assets/${card.color}_reverse.png`;
    } else {
        return `assets/${card.color}_${card.value}.png`;
    }
}

function createDeck() {
    const newDeck = [];
    colors.forEach(color => {
        newDeck.push({ color, value: '0', type: 'number' });
        for (let i = 1; i <= 9; i++) {
            newDeck.push({ color, value: i.toString(), type: 'number' });
            newDeck.push({ color, value: i.toString(), type: 'number' });
        }
    });

    colors.forEach(color => {
        actions.forEach(action => {
            newDeck.push({ color, value: action, type: 'action' });
            newDeck.push({ color, value: action, type: 'action' });
        });
    });

    for (let i = 0; i < 4; i++) {
        newDeck.push({ color: 'wild', value: 'wild', type: 'wild' });
        newDeck.push({ color: 'wild', value: '+4', type: 'wild' });
    }
    return shuffle(newDeck);
}

function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

function startGame() {
    if (playerBalance < 100) {
        updateStatus('Saldo tidak cukup! Minimal saldo $100 untuk bermain.');
        return;
    }
    document.getElementById('modalBalance').textContent = playerBalance;
    document.getElementById('betInput').value = '';
    document.getElementById('betModal').classList.remove('hidden');
}

function placeBet() {
    const betInput = document.getElementById('betInput');
    const betAmount = parseInt(betInput.value);

    if (!betAmount || isNaN(betAmount)) {
        alert('Masukkan jumlah taruhan yang valid!');
        return;
    }
    if (betAmount < 100) {
        alert('Taruhan minimal adalah $100!');
        return;
    }
    if (betAmount > playerBalance) {
        alert('duit kamu gacukup, kerja dulu dong!');
        return;
    }
    
    currentBet = betAmount;
    playerBalance -= currentBet;
    updateBalanceDisplay();
    
    document.getElementById('betModal').classList.add('hidden');
    
    initializeGame();
}

function initializeGame() {
    deck = createDeck();
    playerHand = [];
    aiHand = [];
    discardPile = [];
    currentPlayer = 'player';
    gameActive = true;
    playerCalledUno = false;
    aiCalledUno = false;
    clearUnoTimer();

    for (let i = 0; i < 7; i++) {
        playerHand.push(deck.pop());
    }

    for (let i = 0; i < 7; i++) {
        aiHand.push(deck.pop());
    }

    let firstCard;
    do {
        firstCard = deck.pop();
        if (firstCard.type === 'wild') {
            deck.unshift(firstCard);
            deck = shuffle(deck);
        }
    } while (firstCard.type === 'wild');
    
    discardPile.push(firstCard);
    currentColor = firstCard.color;

    document.getElementById('startButton').style.display = 'none';
    document.getElementById('restartButton').style.display = 'flex';

    updateUI();
    updateStatus('Giliran Anda! Mainkan kartu atau ambil dari tumpukan.');
}

function updateBalanceDisplay() {
    document.getElementById('playerBalance').textContent = `$${playerBalance}`;
    
    if (currentBet > 0 && gameActive) {
        document.getElementById('currentBetDisplay').textContent = `Taruhan: $${currentBet}`;
    } else {
        document.getElementById('currentBetDisplay').textContent = '';
    }
}

function renderCard(card, onClick, index) {
    const div = document.createElement('div');
    div.className = 'card';
    
    const img = document.createElement('img');
    img.src = getCardImage(card);
    img.alt = `${card.color} ${card.value}`;
    
    div.appendChild(img);
    
    if (onClick) {
        div.onclick = () => onClick(index);
    }
    
    return div;
}

function updateUI() {
    const aiCardsDiv = document.getElementById('aiCards');
    aiCardsDiv.innerHTML = '';
    aiHand.forEach(() => {
        const cardBack = document.createElement('div');
        cardBack.className = 'card';
        const img = document.createElement('img');
        img.src = 'assets/card_back.png';
        img.alt = 'Card Back';
        cardBack.appendChild(img);
        aiCardsDiv.appendChild(cardBack);
    });

    const playerCardsDiv = document.getElementById('playerCards');
    playerCardsDiv.innerHTML = '';
    playerHand.forEach((card, index) => {
        const cardDiv = renderCard(card, playCard, index);
        if (currentPlayer !== 'player' || !canPlayCard(card)) {
            cardDiv.style.opacity = '0.5';
            cardDiv.style.cursor = 'not-allowed';
        }
        playerCardsDiv.appendChild(cardDiv);
    });

    if (discardPile.length > 0) {
        const topCard = discardPile[discardPile.length - 1];
        const discardDiv = document.getElementById('discardPile');
        discardDiv.className = 'card';
        discardDiv.innerHTML = '';
        
        const img = document.createElement('img');
        img.src = getCardImage(topCard);
        img.alt = `${topCard.color} ${topCard.value}`;
        discardDiv.appendChild(img);
    }

    document.getElementById('playerCardCount').textContent = `${playerHand.length} kartu`;
    document.getElementById('aiCardCount').textContent = `${aiHand.length} kartu`;
    document.getElementById('deckCount').textContent = `${deck.length} kartu`;
    document.getElementById('currentColor').textContent = `Warna: ${currentColor.toUpperCase()}`;
    
    updateBalanceDisplay();
    updateUnoButtons();
}

function canPlayCard(card) {
    if (!gameActive) return false;
    const topCard = discardPile[discardPile.length - 1];
    
    if (card.value === 'wild') return true;
    
    if (card.value === '+4') {
        const hasOtherPlayableCard = playerHand.some(c => 
            c !== card && (c.color === currentColor || c.value === topCard.value || c.value === 'wild')
        );
        return !hasOtherPlayableCard;
    }

    return card.color === currentColor || card.value === topCard.value;
}

function playCard(index) {
    if (!gameActive) return; 
    if (currentPlayer !== 'player') return;
    
    const card = playerHand[index];
    if (!canPlayCard(card)) {
        updateStatus('Kartu tidak bisa dimainkan! Pilih kartu yang sesuai atau ambil kartu.');
        return;
    }

    hidePassButton();

    playerHand.splice(index, 1);
    discardPile.push(card);

    if (card.type === 'wild') {
        pendingWildCard = card;
        document.getElementById('colorModal').classList.remove('hidden');
        return;
    }

    currentColor = card.color;

    handleCardEffect(card, 'player');
    
    if (playerHand.length === 1 && !playerCalledUno) {
        startUnoTimer('player');
    }
    
    if (playerHand.length === 0) {
        clearUnoTimer();
        endGame('player');
        updateUI();
        return;
    }

    updateUI();
    
    if (currentPlayer === 'ai') {
        setTimeout(aiTurn, 1900);
        updateStatus('Giliran komputer...');
    } else {
        updateStatus('Giliran Anda lagi!');
    }
}

function selectColor(color) {
    document.getElementById('colorModal').classList.add('hidden');
    currentColor = color;
      
    if (pendingWildCard) {
        const cardValue = pendingWildCard.value;
        
        if (cardValue === '+4') {
            for (let i = 0; i < 4; i++) {
                if (deck.length > 0) aiHand.push(deck.pop());
            }
            updateStatus('Komputer mengambil 4 kartu dan dilewati! Giliran Anda lagi.');
        } else {
            currentPlayer = 'ai';
        }
        
        pendingWildCard = null;
    }

    if (playerHand.length === 1 && !playerCalledUno) {
        startUnoTimer('player');
    }

    if (playerHand.length === 0) {
        clearUnoTimer();
        endGame('player');
        updateUI();
        return;
    }

    updateUI();
    if (currentPlayer === 'ai') {
        setTimeout(aiTurn, 1900);
        updateStatus('Giliran komputer...');
    } else {
        updateStatus('Giliran Anda!');
    }
}

function handleCardEffect(card, player) {
    if (card.value === 'skip') {
        updateStatus(`${player === 'player' ? 'Komputer' : 'Anda'} dilewati!`);
        return;
    }

    if (card.value === 'reverse') {
        updateStatus(`Arah permainan dibalik!`);
        return;
    }

    if (card.value === '+2') {
        const target = player === 'player' ? aiHand : playerHand;
        for (let i = 0; i < 2; i++) {
            if (deck.length > 0) target.push(deck.pop());
        }
        updateStatus(`${player === 'player' ? 'Komputer' : 'Anda'} mengambil 2 kartu dan dilewati!`);
        return;
    }

    if (card.value === '+4') {
        const target = player === 'player' ? aiHand : playerHand;
        for (let i = 0; i < 4; i++) {
            if (deck.length > 0) target.push(deck.pop());
        }
        updateStatus(`${player === 'player' ? 'Komputer' : 'Anda'} mengambil 4 kartu dan dilewati!`);
        return;
    }

    currentPlayer = currentPlayer === 'player' ? 'ai' : 'player';
}

function drawCard() {
    if (!gameActive) return;
    if (currentPlayer !== 'player') return;
    
    if (deck.length === 0) {
        const topCard = discardPile.pop();
        deck = shuffle(discardPile);
        discardPile = [topCard];
    }

    if (deck.length > 0) {
        const drawnCard = deck.pop();
        playerHand.push(drawnCard);
        
        if (playerHand.length > 1) {
            playerCalledUno = false;
            clearUnoTimer();
        }
        
        updateUI();
        
        if (canPlayCard(drawnCard)) {
            updateStatus('Kartu yang diambil bisa dimainkan! Pilih kartu tersebut untuk memainkannya, atau klik "Lewati Giliran".');
            showPassButton();
        } else {
            updateStatus('Kartu tidak bisa dimainkan. Giliran komputer.');
            currentPlayer = 'ai';
            updateUI();
            setTimeout(aiTurn, 1900);
        }
    }
}

function showPassButton() {
    const passBtn = document.getElementById('passButton');
    if (passBtn) {
        passBtn.classList.remove('hidden');
    }
}

function hidePassButton() {
    const passBtn = document.getElementById('passButton');
    if (passBtn) {
        passBtn.classList.add('hidden');
    }
}

function passTurn() {
    hidePassButton();
    updateStatus('Anda melewati giliran. Giliran komputer.');
    currentPlayer = 'ai';
    updateUI();
    setTimeout(aiTurn, 1900);
}

function aiTurn() {
    if (!gameActive) return;
    
    if (currentPlayer !== 'player') {
        updateStatus('Giliran komputer...');
        
        let playableIndex = -1;
        for (let i = 0; i < aiHand.length; i++) {
            if (canPlayCard(aiHand[i])) {
                playableIndex = i;
                break;
            }
        }

        if (playableIndex !== -1) {
            const card = aiHand[playableIndex];
            aiHand.splice(playableIndex, 1);
            discardPile.push(card);

            if (card.type === 'wild') {
                const colorCounts = { red: 0, blue: 0, green: 0, yellow: 0 };
                aiHand.forEach(c => {
                    if (colors.includes(c.color)) colorCounts[c.color]++;
                });
                currentColor = Object.keys(colorCounts).reduce((a, b) => 
                    colorCounts[a] > colorCounts[b] ? a : b
                );
            } else {
                currentColor = card.color;
            }

            handleCardEffect(card, 'ai');

            if (aiHand.length === 1 && !aiCalledUno) {
                if (Math.random() < 0.5) {
                    setTimeout(() => {
                        aiCalledUno = true;
                        updateStatus('Komputer memanggil UNO!');
                        clearUnoTimer();
                        updateUnoButtons();
                        setTimeout(() => {
                            if (currentPlayer === 'player') {
                                updateStatus('Giliran Anda!');
                            } else {
                                aiTurn();
                            }
                        }, 2000);
                    }, 500);
                } else {
                    updateStatus('Komputer punya 1 kartu! Apakah dia lupa memanggil UNO? ');
                    startUnoTimer('ai');
                }
            }

            if (aiHand.length === 0) {
                clearUnoTimer();
                endGame('ai');
                updateUI();
                return;
            }

            updateUI();
            
            if (currentPlayer === 'player') {
                updateStatus('Giliran Anda!');
            } else {
                setTimeout(aiTurn, 1900);
            }
        } else {
            if (deck.length === 0) {
                const topCard = discardPile.pop();
                deck = shuffle(discardPile);
                discardPile = [topCard];
            }
            
            if (deck.length > 0) {
                aiHand.push(deck.pop());
                
                if (aiHand.length > 1) {
                    aiCalledUno = false;
                    clearUnoTimer();
                }
                
                updateStatus('Komputer mengambil kartu. Giliran Anda!');
                currentPlayer = 'player';
                updateUI();
            }
        }
    }
}

function endGame(winner) {
    gameActive = false;
    clearUnoTimer();
    
    if (winner === 'player') {
        playerBalance += (currentBet * 2);
        updateBalanceDisplay();
        updateStatus(`Selamat! Anda Menang! Anda memenangkan ${currentBet}!`);
    } else {
        updateBalanceDisplay();
        updateStatus(`Komputer Menang! Anda kehilangan ${currentBet}. Coba Lagi!`);
    }
    
    currentBet = 0;
    updateBalanceDisplay();

    document.getElementById('restartButton').style.display = 'none';
    document.getElementById('startButton').style.display = 'block';
    
    hidePassButton();
    updateUnoButtons();
    
    if (playerBalance < 100) {
        showGameOverModal();
    }
}

function showGameOverModal() {
    const gameOverTitle = document.getElementById('gameOverTitle');
    const gameOverMessage = document.getElementById('gameOverMessage');
    const finalBalance = document.getElementById('finalBalance');
    
    gameOverTitle.textContent = 'GAME OVER';
    gameOverMessage.textContent = 'Saldo Anda telah habis! Anda tidak memiliki cukup saldo untuk melanjutkan permainan.';
    finalBalance.textContent = `${playerBalance}`;
    
    document.getElementById('gameOverModal').classList.remove('hidden');
}

function resetGameToStart() {
    playerBalance = INITIAL_BALANCE;
    currentBet = 0;
    gameActive = false;
    
    document.getElementById('gameOverModal').classList.add('hidden');
    document.getElementById('betModal').classList.add('hidden');
    document.getElementById('restartModal').classList.add('hidden');
    document.getElementById('colorModal').classList.add('hidden');

    document.getElementById('restartButton').style.display = 'none';
    document.getElementById('startButton').style.display = 'block';
    
    updateBalanceDisplay();
    updateStatus('Tekan "Mulai Game" untuk bermain');
    updateUI();
}

function updateStatus(message) {
    document.getElementById('gameStatus').textContent = message;
}

function confirmRestart() {
    document.getElementById('restartModal').classList.remove('hidden');
}

function cancelRestart() {
    document.getElementById('restartModal').classList.add('hidden');
}

function executeRestart() {
    if (currentBet > 0) {
        playerBalance += currentBet;
        currentBet = 0;
        updateBalanceDisplay();
    }
    
    document.getElementById('restartModal').classList.add('hidden');
    hidePassButton();

    gameActive = false;
    document.getElementById('restartButton').style.display = 'none';
    document.getElementById('startButton').style.display = 'block';
    
    updateStatus('Tekan "Mulai Game" untuk bermain');
}

function startUnoTimer(player) {
    clearUnoTimer();
    unoTimeLeft = 5;
    canCatchUno = true;
    
    if (player === 'player') {
        updateStatus('TEKAN TOMBOL UNO! Waktu tersisa: 5 detik');
        
        const botCatchChance = Math.random();
        if (botCatchChance < 0.3) {
            const catchDelay = Math.floor(Math.random() * 2000) + 1000;
            setTimeout(() => {
                if (!playerCalledUno && playerHand.length === 1 && gameActive) {
                    clearUnoTimer();
                    updateStatus('Komputer menangkap Anda! Anda lupa memanggil UNO!');
                    setTimeout(() => {
                        applyUnoPenalty('player');
                    }, 1900);
                }
            }, catchDelay);
        }
    } else if (player === 'ai') {
        updateStatus('Komputer punya 1 kartu tapi belum panggil UNO! Cepat tangkap! Sisa: 5 detik');
    }
    
    updateUnoButtons();
    
    unoTimer = setInterval(() => {
        unoTimeLeft--;
        
        if (player === 'player') {
            updateStatus(`TEKAN TOMBOL UNO! Waktu tersisa: ${unoTimeLeft} detik`);
        } else if (player === 'ai') {
            updateStatus(`Komputer punya 1 kartu tapi belum panggil UNO! Cepat tangkap! Sisa: ${unoTimeLeft} detik`);
        }
        
        if (unoTimeLeft <= 0) {
            clearUnoTimer();
            if (player === 'player' && !playerCalledUno) {
                applyUnoPenalty('player');
            } else if (player === 'ai' && !aiCalledUno) {
                applyUnoPenalty('ai');
            }
        }
    }, 1000);
}

function clearUnoTimer() {
    if (unoTimer) {
        clearInterval(unoTimer);
        unoTimer = null;
    }
    unoTimeLeft = 0;
    canCatchUno = false;
    updateUnoButtons();
}

function callUno() {
    if (playerHand.length !== 1) return;
    
    playerCalledUno = true;
    clearUnoTimer();
    updateStatus('UNO! Anda berhasil memanggil UNO!');
    updateUnoButtons();
    
    setTimeout(() => {
        if (currentPlayer === 'ai') {
            updateStatus('Giliran komputer...');
            aiTurn();
        } else {
            updateStatus('Giliran Anda! (UNO sudah dipanggil ✅)');
        }
    }, 1900);
}

function catchUno() {
    if (!canCatchUno) {
        updateStatus('Belum ada yang bisa ditangkap saat ini!');
        return;
    }
    
    if (aiHand.length === 1 && !aiCalledUno) {
        clearUnoTimer();
        applyUnoPenalty('ai');
        updateStatus('BERHASIL! Anda menangkap komputer yang lupa memanggil UNO!');
    } else {
        updateStatus('Tidak ada yang lupa memanggil UNO!');
    }
    
    setTimeout(() => {
        if (currentPlayer === 'player') {
            updateStatus('Giliran Anda!');
        }
    }, 2500);
}

function applyUnoPenalty(player) {
    const target = player === 'player' ? playerHand : aiHand;
    
    for (let i = 0; i < 2; i++) {
        if (deck.length === 0) {
            const topCard = discardPile.pop();
            deck = shuffle(discardPile);
            discardPile = [topCard];
        }
        if (deck.length > 0) {
            target.push(deck.pop());
        }
    }
    
    if (player === 'player') {
        playerCalledUno = false;
        updateStatus('Anda lupa memanggil UNO! Penalti: +2 kartu');
    } else {
        aiCalledUno = false;
        updateStatus('Komputer lupa memanggil UNO! Penalti: +2 kartu untuk komputer!');
    }
    
    canCatchUno = false;
    updateUI();
    
    setTimeout(() => {
        if (currentPlayer === 'ai') {
            aiTurn();
        } else if (currentPlayer === 'player') {
            updateStatus('Giliran Anda!');
        }
    }, 2500);
}

function updateUnoButtons() {
    const unoBtn = document.getElementById('unoButton');
    const catchBtn = document.getElementById('catchUnoButton');
    const playerUnoStatus = document.getElementById('playerUnoStatus');
    const aiUnoStatus = document.getElementById('aiUnoStatus');
    
    if (unoBtn) {
        if (playerHand.length === 1 && !playerCalledUno && gameActive) {
            unoBtn.classList.remove('hidden');
        } else {
            unoBtn.classList.add('hidden');
        }
    }
    
    if (playerUnoStatus) {
        if (playerHand.length === 1 && playerCalledUno && gameActive) {
            playerUnoStatus.classList.remove('hidden');
        } else {
            playerUnoStatus.classList.add('hidden');
        }
    }

    if (aiUnoStatus) {
        if (aiHand.length === 1 && aiCalledUno && gameActive) {
            aiUnoStatus.classList.remove('hidden');
        } else {
            aiUnoStatus.classList.add('hidden');
        }
    }
    
    if (catchBtn) {
        if (canCatchUno && aiHand.length === 1 && !aiCalledUno && gameActive) {
            catchBtn.classList.remove('hidden');
        } else {
            catchBtn.classList.add('hidden');
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('colorModal').classList.add('hidden');
    document.getElementById('betModal').classList.add('hidden');
    document.getElementById('restartModal').classList.add('hidden');
    document.getElementById('gameOverModal').classList.add('hidden');
    
    updateBalanceDisplay();
    updateStatus('Tekan "Mulai Game" untuk bermain');
});