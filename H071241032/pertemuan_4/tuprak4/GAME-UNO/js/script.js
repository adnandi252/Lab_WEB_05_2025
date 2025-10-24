document.addEventListener('DOMContentLoaded', () => {
    const ui = {
        playerHand: document.getElementById('player-hand'),
        botHand: document.getElementById('bot-hand'),
        discardPile: document.getElementById('discard-pile'),
        deck: document.getElementById('deck'),
        gameMessage: document.getElementById('game-message'),
        playerBalance: document.getElementById('player-balance'),
        startRoundBtn: document.getElementById('start-round-btn'),
        betInput: document.getElementById('bet-input'),
        currentBalanceSpan: document.getElementById('current-balance'),
        unoButton: document.getElementById('uno-button'),
        colorPickerOverlay: document.getElementById('color-picker-overlay'),
        colorChoices: document.getElementById('color-choices'),
        gameOverOverlay: document.getElementById('game-over-overlay'),
        restartGameBtn: document.getElementById('restart-game-btn'),
        bettingOverlay: document.getElementById('betting-overlay'),
    };
    let gameState = {
        deck: [],
        playerHand: [],
        botHand: [],
        discardPile: [],
        currentPlayer: 'player',
        currentColor: '',
        playerBalance: 5000,
        currentBet: 0,
        gameActive: false,
        playerDeclaredUno: false,
    };


    function createDeck() {
        const colors = ['red', 'green', 'blue', 'yellow'];
        const actionValues = ['Skip', 'Reverse', 'DrawTwo'];
        let deck = [];

        colors.forEach(color => {
            deck.push({ color, value: '0' });
            for (let i = 1; i <= 9; i++) {
                deck.push({ color, value: String(i) }, { color, value: String(i) });
            }
            actionValues.forEach(value => {
                deck.push({ color, value }, { color, value });
            });
        });

        for (let i = 0; i < 4; i++) {
            deck.push({ color: 'Wild', value: 'Wild' }, { color: 'Wild', value: 'WildDrawFour' });
        }
        return deck;
    }

    function shuffleDeck(deck) {
        for (let i = deck.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [deck[i], deck[j]] = [deck[j], deck[i]];
        }
    }

   
    function dealInitialCards() {
        for (let i = 0; i < 7; i++) {
            gameState.playerHand.push(gameState.deck.pop());
            gameState.botHand.push(gameState.deck.pop());
        }
    }


    function getCardImagePath(card) {
        let imageName = '';
        const color = card.color.toLowerCase();
        if (card.value === 'WildDrawFour') imageName = 'plus_4.png';
        else if (card.value === 'Wild') imageName = 'wild.png';
        else if (card.value === 'DrawTwo') imageName = `${color}_plus2.png`;
        else {
            const value = card.value.toLowerCase();
            imageName = `${color}_${value}.png`;
        }
        return `url('assets/cards/${imageName}')`;
    }

    function createCardElement(card, index) {
        const cardElement = document.createElement('div');
        cardElement.className = 'card';
        cardElement.style.backgroundImage = getCardImagePath(card);
        if (index !== undefined) {
            cardElement.dataset.cardIndex = index;
        }
        return cardElement;
    }

 
    function renderPlayerHand() {
        ui.playerHand.innerHTML = '';
        gameState.playerHand.forEach((card, index) => {
            ui.playerHand.appendChild(createCardElement(card, index));
        });
    }

   
    function renderBotHand() {
        ui.botHand.innerHTML = '';
        gameState.botHand.forEach(() => {
            const cardElement = document.createElement('div');
            cardElement.className = 'card facedown';
            cardElement.style.backgroundImage = `url('assets/card_back.png')`;
            ui.botHand.appendChild(cardElement);
        });
    }

 
    function renderDiscardPile() {
        ui.discardPile.innerHTML = '';
        if (gameState.discardPile.length === 0) return;
        const topCard = gameState.discardPile[gameState.discardPile.length - 1];
        const cardElement = createCardElement(topCard);
        if (topCard.color === 'Wild') {
            cardElement.style.borderColor = gameState.currentColor;
            cardElement.style.borderWidth = '4px';
            cardElement.style.borderStyle = 'solid';
        }
        ui.discardPile.appendChild(cardElement);
    }
 
    function highlightPlayableCards() {
        const topCard = gameState.discardPile[gameState.discardPile.length - 1];
        ui.playerHand.querySelectorAll('.card').forEach(cardElement => {
            const cardIndex = cardElement.dataset.cardIndex;
            const card = gameState.playerHand[cardIndex];
            cardElement.classList.toggle('playable', isValidMove(card, topCard));
        });
    }

 
    function updateBalanceDisplay() {
        const balanceText = `$${gameState.playerBalance}`;
        ui.playerBalance.textContent = balanceText;
        ui.currentBalanceSpan.textContent = gameState.playerBalance;
    }

    /**
     * Fungsi utama untuk memanggil semua fungsi render sekaligus.
     * Mengupdate seluruh tampilan visual permainan.
     */
    function renderGame() {
        renderPlayerHand();
        renderBotHand();
        renderDiscardPile();
        highlightPlayableCards();
    }

    // =================================================================
    // BAGIAN 4: Logika Inti Permainan
    // =================================================================
    

    function isValidMove(card, topCard) {
        if (!topCard) return true;
        return card.color === 'Wild' || card.color === gameState.currentColor || card.value === topCard.value;
    }

    
    function drawCards(player, amount) {
        const hand = (player === 'player') ? gameState.playerHand : gameState.botHand;
        for (let i = 0; i < amount; i++) {
            if (gameState.deck.length > 0) {
                hand.push(gameState.deck.pop());
            }
        }
    }

    
    function applyCardEffect(card, playedBy) {
        const target = (playedBy === 'player') ? 'bot' : 'player';
        let amountToDraw = 0;
        if (card.value === 'DrawTwo') amountToDraw = 2;
        if (card.value === 'WildDrawFour') amountToDraw = 4;

        if (amountToDraw > 0) {
            ui.gameMessage.textContent = `${target === 'player' ? 'Anda' : 'Bot'} mengambil ${amountToDraw} kartu!`;
            drawCards(target, amountToDraw);
        }
    }
    
    /**
     * Mengatur perpindahan giliran antara pemain dan bot.
     * Fungsi ini juga menangani penalti jika pemain lupa menekan "UNO!".
     */
    function switchTurn() {
        if (!gameState.gameActive) return;

        // Jika giliran pemain yang akan berakhir...
        if (gameState.currentPlayer === 'player') {
            // Langsung ubah status untuk mencegah pemain klik kartu lagi.
            gameState.currentPlayer = 'bot';
            ui.gameMessage.textContent = 'Giliran Bot...';
            
            // Beri jeda 2 detik untuk memberi kesempatan menekan UNO.
            setTimeout(() => {
                // Pengecekan penalti dilakukan di sini, setelah jeda.
                if (gameState.playerHand.length === 1 && !gameState.playerDeclaredUno) {
                    ui.gameMessage.textContent = 'Anda lupa menekan UNO! Penalti +2 kartu.';
                    drawCards('player', 2);
                    renderGame();
                }
                
                gameState.playerDeclaredUno = false; // Reset status untuk giliran berikutnya.
                botTurn(); // Mulai giliran bot.

            }, 2000);
        } else {
            // Jika giliran bot yang berakhir, langsung pindah ke pemain.
            gameState.currentPlayer = 'player';
            ui.gameMessage.textContent = 'Giliran Anda!';
            highlightPlayableCards();
        }
    }

    function playCard(player, cardIndex) {
        const hand = (player === 'player') ? gameState.playerHand : gameState.botHand;
        const card = hand.splice(cardIndex, 1)[0];
        gameState.discardPile.push(card);
        
        gameState.playerDeclaredUno = false; // Reset status UNO setiap main kartu.
        
        if (card.color !== 'Wild') {
            gameState.currentColor = card.color;
        }

        applyCardEffect(card, player);
        renderGame();

        // Cek kondisi kemenangan.
        if (hand.length === 0) {
            endRound(player);
            return;
        }
        
        // Logika setelah kartu dimainkan.
        if (card.color === 'Wild') {
            showColorPicker(player);
        } else if (['Skip', 'Reverse', 'DrawTwo'].includes(card.value)) {
            ui.gameMessage.textContent = 'Kartu Aksi! Giliran Anda lagi!';
            if (player === 'bot') {
                setTimeout(botTurn, 1500);
            }
        } else {
            switchTurn();
        }
    }
    
    /**
     * Aksi saat pemain mengklik dek untuk mengambil kartu.
     */
    function drawCardFromDeck() {
        if (!gameState.gameActive || gameState.currentPlayer !== 'player' || gameState.deck.length === 0) return;
        drawCards('player', 1);
        ui.gameMessage.textContent = `Anda mengambil kartu. Giliran dilewati.`;
        renderGame();
        // Langsung panggil switchTurn agar alur jeda dan giliran tetap konsisten.
        switchTurn();
    }
    
 
    function showColorPicker(player) {
        if (player === 'player') {
            ui.colorPickerOverlay.classList.add('active');
        } else {
            // Bot akan memilih warna yang paling banyak dimilikinya.
            const colorsInHand = gameState.botHand.map(card => card.color).filter(color => color !== 'Wild');
            if (colorsInHand.length === 0) {
                handleColorChoice('red'); return;
            }
            const colorCounts = colorsInHand.reduce((acc, color) => {
                acc[color] = (acc[color] || 0) + 1; return acc;
            }, {});
            const chosenColor = Object.keys(colorCounts).reduce((a, b) => colorCounts[a] > colorCounts[b] ? a : b);
            handleColorChoice(chosenColor);
        }
    }
    
  
    function handleColorChoice(color) {
        gameState.currentColor = color;
        ui.gameMessage.textContent = `Warna diubah menjadi ${color}.`;
        ui.colorPickerOverlay.classList.remove('active');
        renderDiscardPile();
        switchTurn();
    }
    
    // =================================================================
    // BAGIAN 5: Logika Bot & Alur Permainan
    // =================================================================

    /**
     * Logika untuk giliran Bot. Bot akan "berpikir" selama 1.5 detik.
     */
    function botTurn() {
        if (!gameState.gameActive || gameState.currentPlayer !== 'bot') return;
        ui.gameMessage.textContent = 'Bot sedang berpikir...';
        
        // Pisahkan logika berpikir agar pesan bisa muncul dulu.
        setTimeout(() => {
            const topCard = gameState.discardPile[gameState.discardPile.length - 1];
            // 1. Cari kartu berwarna yang bisa dimainkan.
            let playableCardIndex = gameState.botHand.findIndex(card => card.color !== 'Wild' && isValidMove(card, topCard));
            // 2. Jika tidak ada, cari kartu Wild.
            if (playableCardIndex === -1) {
                playableCardIndex = gameState.botHand.findIndex(card => card.color === 'Wild' && isValidMove(card, topCard));
            }
            // 3. Validasi aturan Wild Draw Four.
            if (playableCardIndex !== -1 && gameState.botHand[playableCardIndex].value === 'WildDrawFour') {
                if (gameState.botHand.some(c => c.color === gameState.currentColor)) {
                    playableCardIndex = -1; // Tidak boleh main jika ada warna cocok.
                }
            }

            // 4. Mainkan kartu atau ambil dari dek.
            if (playableCardIndex !== -1) {
                playCard('bot', playableCardIndex);
            } else {
                if (gameState.deck.length > 0) {
                    drawCards('bot', 1);
                    ui.gameMessage.textContent = 'Bot mengambil satu kartu.';
                    renderBotHand();
                    setTimeout(switchTurn, 1000);
                } else {
                    switchTurn(); // Jika dek kosong.
                }
            }
        }, 1500);
    }
    
    
    function handlePlayerCardClick(e) {
        // Guard clause untuk memastikan hanya pemain yang bisa klik di gilirannya.
        if (!gameState.gameActive || gameState.currentPlayer !== 'player') return;
        
        const cardElement = e.target.closest('.card');
        if (!cardElement) return;

        const cardIndex = cardElement.dataset.cardIndex;
        const card = gameState.playerHand[cardIndex];
        const topCard = gameState.discardPile[gameState.discardPile.length - 1];

        if (!isValidMove(card, topCard)) {
            ui.gameMessage.textContent = 'Kartu tidak cocok!';
            return;
        }
        
        // Validasi aturan Wild Draw Four.
        if (card.value === 'WildDrawFour' && gameState.playerHand.some(c => c.color === gameState.currentColor)) {
            alert('Anda tidak bisa memainkan Wild +4 karena masih punya kartu dengan warna yang cocok!');
            return;
        }
        playCard('player', cardIndex);
    }

    
    function endRound(winner) {
        gameState.gameActive = false;
        
        if (winner === 'player') {
            ui.gameMessage.textContent = `Selamat! Anda memenangkan ronde dan mendapat $${gameState.currentBet}!`;
            gameState.playerBalance += (gameState.currentBet * 2);
        } else {
            ui.gameMessage.textContent = `Sayang sekali! Bot memenangkan ronde ini.`;
        }
        updateBalanceDisplay();
        
        if (gameState.playerBalance <= 0) {
            setTimeout(() => ui.gameOverOverlay.classList.add('active'), 2000);
        } else {
            setTimeout(() => ui.bettingOverlay.classList.add('active'), 3000);
        }
    }
  
    function startGame(bet) {
        gameState.playerBalance -= bet;
        updateBalanceDisplay();
        
        // Reset semua state permainan untuk ronde baru.
        Object.assign(gameState, {
            gameActive: true,
            currentBet: bet,
            playerHand: [],
            botHand: [],
            discardPile: [],
            deck: createDeck(),
            playerDeclaredUno: false
        });
        
        shuffleDeck(gameState.deck);
        dealInitialCards();

        // Pastikan kartu pertama bukan Wild Draw Four.
        let firstCard = gameState.deck.pop();
        while (firstCard.value === 'WildDrawFour') {
            gameState.deck.push(firstCard);
            shuffleDeck(gameState.deck);
            firstCard = gameState.deck.pop();
        }
        gameState.discardPile.push(firstCard);
        gameState.currentColor = firstCard.color;
        gameState.currentPlayer = 'player';
        
        ui.gameMessage.textContent = 'Giliran Anda untuk bermain!';
        renderGame();
        
        // Jika kartu pertama adalah Wild, pemain pertama harus memilih warna.
        if (gameState.discardPile[0].color === 'Wild') {
            setTimeout(() => showColorPicker('player'), 500);
        }
    }

    // =================================================================
    // BAGIAN 6: Event Listeners
    // =================================================================

    /**
     * Mendaftarkan semua event listener untuk interaksi UI.
     */
    function setupEventListeners() {
        // Tombol untuk memulai ronde.
        ui.startRoundBtn.addEventListener('click', () => {
            const betAmount = parseInt(ui.betInput.value);
            if (isNaN(betAmount) || betAmount < 100) return alert("Taruhan minimal adalah $100.");
            if (betAmount > gameState.playerBalance) return alert("Saldo Anda tidak mencukupi untuk taruhan ini.");
            ui.bettingOverlay.classList.remove('active');
            startGame(betAmount);
        });
        
        // Klik pada area tangan pemain.
        ui.playerHand.addEventListener('click', handlePlayerCardClick);
        // Klik pada dek untuk mengambil kartu.
        ui.deck.addEventListener('click', drawCardFromDeck);

        // Tombol UNO.
        ui.unoButton.addEventListener('click', () => {
            if (gameState.playerHand.length !== 1) {
                ui.gameMessage.textContent = 'Salah pencet UNO! Anda mendapat 2 kartu penalti.';
                drawCards('player', 2);
                renderGame();
            } else {
                ui.gameMessage.textContent = 'Anda berhasil memanggil UNO!';
                gameState.playerDeclaredUno = true;
            }
        });

        // Pilihan warna pada pop-up.
        ui.colorChoices.addEventListener('click', (e) => {
            if (e.target.classList.contains('color-btn')) {
                handleColorChoice(e.target.dataset.color);
            }
        });

        // Tombol untuk memulai ulang game setelah Game Over.
        ui.restartGameBtn.addEventListener('click', () => {
            gameState.playerBalance = 5000;
            updateBalanceDisplay();
            ui.gameOverOverlay.classList.remove('hidden');
            ui.bettingOverlay.classList.add('active');
            ui.gameMessage.textContent = 'Masukkan taruhan untuk memulai ronde baru.';
        });
    }

    // Inisialisasi awal saat halaman dimuat.
    updateBalanceDisplay();
    setupEventListeners();
});