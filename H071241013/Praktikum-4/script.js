// =======================================================
// VAR & KONSTANTA
// =======================================================

let deck = [];
let playerHand = [];
let botHand = [];
let discardPile = [];

const gameState = {
    saldo: 5000,
    taruhan: 0,
    turn: 'player',             // 'player' atau 'bot'
    direction: 1,               // untuk lebih dari 2 pemain (tidak terlalu dipakai di 2 pemain)
    currentColor: '',
    currentValue: '',
    unoTimers: { player: null, bot: null },
    unoCalled: { player: false, bot: false },
    isBettingPhase: true,
    canDrawAndPlay: false,      // setelah draw, pemain boleh langsung mainkan kartu yang diambil
    pendingDraw: 0              // jumlah kartu yang harus ditarik oleh pemain berikutnya (karena +2/+4)
};

const COLORS = ['red', 'blue', 'green', 'yellow'];
const ACTION_CARDS = ['skip', 'reverse', 'plus2'];
const WILD_TYPES = ['wild', 'plus4'];

// DOM
const $playerHand = document.getElementById('player-hand');
const $botHand = document.getElementById('bot-hand');
const $topCard = document.getElementById('top-card');
const $drawCardBtn = document.getElementById('draw-card-btn');
const $unoBtn = document.getElementById('uno-btn');
const $betModal = document.getElementById('bet-modal');
const $betInput = document.getElementById('bet-input');
const $startRoundBtn = document.getElementById('start-round-btn');
const $saldoDisplay = document.getElementById('saldo-display');
const $betDisplay = document.getElementById('bet-display');
const $turnIndicator = document.getElementById('turn-indicator');
const $gameMessage = document.getElementById('game-message');
const $colorPicker = document.getElementById('color-picker');
const $gameOverModal = document.getElementById('game-over-modal');
const $skipDrawBtn = document.getElementById('skip-draw-btn');
const $restartGameBtn = document.getElementById('restart-game-btn');

// =======================================================
// DECK CREATION & SHUFFLE (UNO STANDARD COUNTS)
// =======================================================
function createDeck() {
    deck = [];

    // For each color:
    for (const color of COLORS) {
        // one 0
        deck.push({ color, value: '0', type: 'number' });

        // two copies of 1-9
        for (let i = 1; i <= 9; i++) {
            deck.push({ color, value: String(i), type: 'number' });
            deck.push({ color, value: String(i), type: 'number' });
        }

        // two copies of each action card per color
        for (const action of ACTION_CARDS) {
            deck.push({ color, value: action, type: 'action' });
            deck.push({ color, value: action, type: 'action' });
        }
    }

    // 4 wild and 4 plus4
    for (let i = 0; i < 4; i++) {
        deck.push({ color: 'wild', value: 'wild', type: 'wild' });
        deck.push({ color: 'wild', value: 'plus4', type: 'wild' });
    }

    shuffleDeck();
}

function shuffleDeck() {
    for (let i = deck.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [deck[i], deck[j]] = [deck[j], deck[i]];
    }
}

// =======================================================
// DEAL & START
// =======================================================
function dealCards() {
    playerHand = [];
    botHand = [];
    discardPile = [];

    // deal 7 each
    for (let i = 0; i < 7; i++) {
        playerHand.push(deck.pop());
        botHand.push(deck.pop());
    }

    // choose top card that is NOT a wild (preferably). If wild comes up, put back and reshuffle.
    let top;
    do {
        if (deck.length === 0) {
            // shouldn't happen but rebuild deck from discard
            break;
        }
        top = deck.pop();
        // If wild/plus4 appear as opening card, shuffle into deck and pick another
        if (top.type === 'wild') {
            deck.unshift(top);
            shuffleDeck();
            top = null;
        }
    } while (!top);

    // If still null (very unlikely), fallback to first non-wild in players' hands
    if (!top) {
        // try find non-wild in player or bot hand
        let found = false;
        for (let i = 0; i < playerHand.length && !found; i++) {
            if (playerHand[i].type !== 'wild') {
                top = playerHand.splice(i, 1)[0];
                found = true;
            }
        }
        for (let i = 0; i < botHand.length && !found; i++) {
            if (botHand[i].type !== 'wild') {
                top = botHand.splice(i, 1)[0];
                found = true;
            }
        }
        if (!found) {
            // as final fallback, pop from deck even if wild
            top = deck.pop();
        }
    }

    discardPile.push(top);
    gameState.currentColor = top.color === 'wild' ? COLORS[Math.floor(Math.random()*4)] : top.color;
    gameState.currentValue = top.value;

    // If initial card is action (skip/reverse/+2), apply its effects properly.
    if (top.type === 'action') {
        // For 2-player mode: reverse acts as skip
        applyActionCardInitial(top);
    }

    // Reset state
    gameState.turn = 'player';
    gameState.pendingDraw = 0;
    gameState.canDrawAndPlay = false;
    gameState.unoCalled.player = false;
    gameState.unoCalled.bot = false;
    clearAllUnoTimers();

    updateUI();
}

// special handling for initial action on top of discard
function applyActionCardInitial(card) {
    if (card.value === 'skip' || card.value === 'reverse') {
        // skip bot once (initial)
        displayMessage(`Kartu awal: ${card.value.toUpperCase()} — Bot dilewati!`, 'action');
        // keep turn as player (bot skipped)
        gameState.turn = 'player';
    } else if (card.value === 'plus2') {
        displayMessage(`Kartu awal: +2 — Bot mengambil 2 kartu!`, 'action');
        drawCards(botHand, 2);
        gameState.turn = 'player'; // player starts after initial effect
    }
}

// =======================================================
// VALIDATION & DRAW
// =======================================================
function checkValidPlay(card, topCard) {
    // Wilds always can be played
    if (card.type === 'wild') return true;

    // matching color or matching value
    if (card.color === gameState.currentColor) return true;
    if (topCard && card.value === topCard.value) return true;

    return false;
}

function drawCards(targetHand, count) {
    for (let i = 0; i < count; i++) {
        if (deck.length === 0) {
            // refill deck from discard pile (keep top)
            const top = discardPile.pop();
            deck = shuffleArray(discardPile);
            discardPile = [top];
        }
        if (deck.length === 0) break; // no cards left
        targetHand.push(deck.pop());
    }
    updateUI();
}

// utility shuffle returning new array
function shuffleArray(arr) {
    const a = arr.slice();
    for (let i = a.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
}

// =======================================================
// PLAY CARD & ACTION HANDLING
// =======================================================
function playCard(cardIndex, isBot = false) {
    const hand = isBot ? botHand : playerHand;
    if (cardIndex < 0 || cardIndex >= hand.length) return false;
    const card = hand[cardIndex];
    const topCard = discardPile[discardPile.length - 1];

    // If there's a pendingDraw on next player, they cannot play — they must draw (rule variant).
    // Here we implement: pendingDraw must be drawn by the next player when it's their turn.
    if (!isBot && gameState.turn === 'player' && gameState.pendingDraw > 0) {
        displayMessage(`Anda harus mengambil ${gameState.pendingDraw} kartu karena efek sebelumnya.`, 'error');
        return false;
    }

    // Validate play for non-wild cards
    if (!isBot && card.type !== 'wild' && !checkValidPlay(card, topCard)) {
        displayMessage("Kartu tidak valid! Mainkan kartu yang sesuai warna atau angka/simbol.", 'error');
        return false;
    }

    // Wild Draw Four rule: user may only play if they have no other valid non-wild cards
    if (!isBot && card.value === 'plus4') {
        const otherValid = playerHand.some((c, idx) => idx !== cardIndex && checkValidPlay(c, topCard));
        if (otherValid) {
            displayMessage("❌ Wild Draw Four hanya boleh dimainkan jika tidak ada kartu lain yang bisa dimainkan.", 'error');
            return false;
        }
    }

    // move card to discard
    hand.splice(cardIndex, 1);
    discardPile.push(card);

    // reset UNO timer/state for that player if they had 1 -> played
    clearUnoTimer(isBot ? 'bot' : 'player');
    gameState.unoCalled.player = false;
    gameState.unoCalled.bot = false;
    $unoBtn.disabled = true;

    // update current color/value (wild handled separately)
    if (card.type === 'wild') {
        // for wild display; color will be chosen after play
        gameState.currentValue = card.value;
        // show color picker if player
        if (!isBot) {
            $colorPicker.classList.remove('hidden');
            displayMessage("Pilih warna baru (untuk Wild / +4).", 'action');
            // don't advance turn yet; wait for player to pick color (handleNextTurn will be called after color chosen)
            updateUI();
            return true;
        } else {
            // bot chooses color immediately
            botChooseColor();
            // apply +4 if plus4
            if (card.value === 'plus4') {
                displayMessage("🤖 Bot memainkan +4 — Anda mengambil 4 kartu!", 'action');
                drawCards(playerHand, 4);
                // skip player's next turn (in two-player, player loses turn)
                // we'll set pendingDraw to 0 since we already applied draw; next handleNextTurn will move to player but we want to skip them -> so toggle turn twice
                handleNextTurn(true); // advance turn (bot played)
                return true;
            } else {
                // wild (no draw)
                handleNextTurn(true);
                return true;
            }
        }
    } else {
        // non-wild play: set color to card.color and value
        gameState.currentColor = card.color;
        gameState.currentValue = card.value;
    }

    // If player empties hand -> win
    if (hand.length === 0) {
        endRound(isBot ? 'bot' : 'player');
        updateUI();
        return true;
    }

    // If player now has 1 card -> start UNO timer for that player
    if (hand.length === 1) {
        startUnoTimer(isBot ? 'bot' : 'player');
        if (isBot) {
            // Bot auto-calls UNO immediately
            gameState.unoCalled.bot = true;
            clearUnoTimer('bot');
            displayMessage("🤖 Bot menekan UNO!", 'uno');
        } else {
            $unoBtn.disabled = false;
            displayMessage("Anda memiliki 1 kartu! Tekan UNO dalam 5 detik!", 'action');
        }
    }

    // apply action card effects (skip/reverse/+2)
    if (card.type === 'action') {
        applyActionCard(card, isBot);
        // Note: applyActionCard handles draw to opponent and sets turn appropriately (skip)
        updateUI();
        return true;
    }

    // normal number card: advance turn
    updateUI();
    handleNextTurn(isBot);
    return true;
}

function applyActionCard(card, playedByBot = false) {
    // card.value in 'skip','reverse','plus2'
    const currentPlayer = playedByBot ? 'Bot' : 'Pemain';
    const nextIsPlayer = (gameState.turn === 'player') ? 'bot' : 'player';
    // For 2 players: reverse acts like skip (skip opponent)
    if (card.value === 'skip' || card.value === 'reverse') {
        displayMessage(`${currentPlayer} memainkan ${card.value.toUpperCase()}! Lawan dilewati.`, 'action');
        // skip next player's turn: we simply toggle turn twice (advance twice)
        // But because we haven't toggled yet in this flow, call handleNextTurn twice
        handleNextTurn(playedByBot); // first toggle to opponent (skipped)
        // After skipping, the current player gets next turn again (i.e., toggle back)
        // But in UNO rules for Skip: the player who played skip ends and the next player is skipped; so current player does NOT immediately play again.
        // Implementation: after playing skip, the turn should go to player after the skipped one. With 2 players, skip means the player who played retains "next" (since opponent skipped) — that can be interpreted differently.
        // We'll make behavior: skip opponent and continue with the player who played (so effectively same player again). To align with common 2-player UNO: Skip means opponent loses their turn -> so control returns to the player who played.
        // So we do not call any further handleNextTurn here; handleNextTurn already called once above returns control appropriately in the flow.
        return;
    } else if (card.value === 'plus2') {
        displayMessage(`${currentPlayer} memainkan +2! Lawan mengambil 2 kartu.`, 'action');
        // give opponent 2 cards immediately
        if (playedByBot) {
            drawCards(playerHand, 2);
        } else {
            drawCards(botHand, 2);
        }
        // skip opponent's turn — next turn remains with the player who played (in 2-player)
        // So call handleNextTurn once to advance to opponent, but we then immediately call handleNextTurn again so player's turn comes back.
        handleNextTurn(playedByBot); // moves to opponent
        // after giving cards and skipping, move to the player again
        // But to keep simpler and visible, we will not auto-play again for the same player; we will set turn to the player who played (so they get next).
        gameState.turn = playedByBot ? 'bot' : 'player';
        $turnIndicator.textContent = `Giliran: ${gameState.turn === 'player' ? 'Pemain' : 'Bot'}`;
        // If it's bot's turn now, call botTurn
        if (gameState.turn === 'bot') setTimeout(botTurn, 800);
        return;
    }
}

// handle +4 (wild) is implemented in playCard when bot plays; for player we used color picker then apply effect
function applyPlus4AfterColorChosen(isBot) {
    // If last discard is plus4, then opponent draws 4 and is skipped
    const last = discardPile[discardPile.length - 1];
    if (!last || last.value !== 'plus4') return;
    if (isBot) {
        // bot played +4 -> player draws and skip
        drawCards(playerHand, 4);
        // skip player's turn: after bot played, bot gets next (so toggle back)
        gameState.turn = 'bot';
        $turnIndicator.textContent = 'Giliran: Bot';
        setTimeout(botTurn, 900);
    } else {
        // player played +4 -> bot draws and skip bot's turn -> player retains
        drawCards(botHand, 4);
        gameState.turn = 'player';
        $turnIndicator.textContent = 'Giliran: Pemain';
    }
}

// =======================================================
// TURN HANDLING & BOT
// =======================================================
function handleNextTurn(playedByBot) {
    // hide skip-draw controls
    $skipDrawBtn.classList.add('hidden');
    gameState.canDrawAndPlay = false;

    // Swap turn
    gameState.turn = (gameState.turn === 'player') ? 'bot' : 'player';
    $turnIndicator.textContent = `Giliran: ${gameState.turn === 'player' ? 'Pemain' : 'Bot'}`;

    // If pending draw (should be handled here) - but in this simplified implementation we applied draw immediately on play
    // Start UNO timer if needed: if opponent has 1 card
    if (gameState.turn === 'player' && playerHand.length === 1) {
        startUnoTimer('player');
        $unoBtn.disabled = false;
    }
    if (gameState.turn === 'bot' && botHand.length === 1) {
        startUnoTimer('bot');
    }

    // If bot to play, call botTurn after small delay
    if (gameState.turn === 'bot') {
        setTimeout(botTurn, 700);
    }
}

// BOT logic (simple heuristic)
function botTurn() {
    $turnIndicator.textContent = 'Giliran: Bot (Berpikir...)';
    const topCard = discardPile[discardPile.length - 1];
    // If bot has pending draw requirement (none in this simplified rule because we applied draw on action), would draw here

    // find all valid plays
    const validIndices = [];
    for (let i = 0; i < botHand.length; i++) {
        if (checkValidPlay(botHand[i], topCard) || botHand[i].type === 'wild') validIndices.push(i);
    }

    let cardIndexToPlay = -1;

    // Prioritize: action (+2, skip/reverse), then number matching color to reduce color counts, then wild, plus4 if no other plays
    // find action +2 first
    for (const idx of validIndices) {
        const c = botHand[idx];
        if (c.type === 'action' && c.value === 'plus2') { cardIndexToPlay = idx; break; }
    }
    if (cardIndexToPlay === -1) {
        for (const idx of validIndices) {
            const c = botHand[idx];
            if (c.type === 'action') { cardIndexToPlay = idx; break; }
        }
    }
    if (cardIndexToPlay === -1) {
        for (const idx of validIndices) {
            const c = botHand[idx];
            if (c.type === 'number') { cardIndexToPlay = idx; break; }
        }
    }
    if (cardIndexToPlay === -1) {
        // wild (not plus4) first
        for (const idx of validIndices) {
            const c = botHand[idx];
            if (c.type === 'wild' && c.value === 'wild') { cardIndexToPlay = idx; break; }
        }
    }
    if (cardIndexToPlay === -1) {
        // plus4 only if no other playable card
        for (let i = 0; i < botHand.length; i++) {
            if (botHand[i].value === 'plus4') {
                // only play plus4 if no other valid card existed
                const otherValid = botHand.some((c, ii) => ii !== i && checkValidPlay(c, topCard));
                if (!otherValid) { cardIndexToPlay = i; break; }
            }
        }
    }

    if (cardIndexToPlay !== -1) {
        const card = botHand[cardIndexToPlay];

        // If bot will go to 1 card after this play, bot should call UNO (we auto-call)
        if (botHand.length === 2) {
            gameState.unoCalled.bot = true;
            clearUnoTimer('bot');
            displayMessage("🤖 Bot menekan UNO!", 'uno');
        }

        // play chosen card
        setTimeout(() => {
            playCard(cardIndexToPlay, true);
        }, 500);
    } else {
        // draw 1 card
        displayMessage("🤖 Bot mengambil kartu...", 'info');
        setTimeout(() => {
            drawCards(botHand, 1);
            const newCard = botHand[botHand.length - 1];
            const top = discardPile[discardPile.length - 1];
            if (checkValidPlay(newCard, top) || newCard.type === 'wild') {
                // play it optionally
                setTimeout(() => {
                    playCard(botHand.length - 1, true);
                }, 400);
            } else {
                displayMessage("🤖 Bot melewatkan giliran.", 'info');
                // pass turn back to player
                handleNextTurn(true);
            }
        }, 500);
    }
}

function botChooseColor() {
    const counts = { red: 0, blue: 0, green: 0, yellow: 0 };
    for (const c of botHand) {
        if (c.color !== 'wild') counts[c.color]++;
    }
    let best = 'blue', max = -1;
    for (const color of COLORS) {
        if (counts[color] > max) { max = counts[color]; best = color; }
    }
    gameState.currentColor = best;
    displayMessage(`🤖 Bot memilih warna ${best.toUpperCase()}!`, 'action');
}

// =======================================================
// UNO: timers, call, penalties
// =======================================================
function startUnoTimer(target) {
    clearUnoTimer(target);
    if (target !== 'player' && target !== 'bot') return;
    gameState.unoCalled[target] = false;

    gameState.unoTimers[target] = setTimeout(() => {
        if (!gameState.unoCalled[target]) {
            // penalize: draw 2
            if (target === 'player') {
                displayMessage("🔔 Anda lupa menekan UNO! Penalti +2 kartu.", 'error');
                drawCards(playerHand, 2);
            } else {
                displayMessage("🔔 Bot lupa menekan UNO! Penalti +2 kartu.", 'error');
                drawCards(botHand, 2);
            }
            gameState.unoTimers[target] = null;
            gameState.unoCalled[target] = false;
            $unoBtn.disabled = true;
            updateUI();
        }
    }, 5000);
}

function clearUnoTimer(target) {
    if (target === 'player' || target === 'bot') {
        if (gameState.unoTimers[target]) {
            clearTimeout(gameState.unoTimers[target]);
            gameState.unoTimers[target] = null;
        }
    } else {
        // clear both
        clearAllUnoTimers();
    }
}

function clearAllUnoTimers() {
    if (gameState.unoTimers.player) { clearTimeout(gameState.unoTimers.player); gameState.unoTimers.player = null; }
    if (gameState.unoTimers.bot) { clearTimeout(gameState.unoTimers.bot); gameState.unoTimers.bot = null; }
}

// handling when player presses UNO button
function handleUnoCall(caller) {
    if (caller === 'player') {
        if (playerHand.length === 1 && gameState.turn === 'player') {
            gameState.unoCalled.player = true;
            clearUnoTimer('player');
            $unoBtn.disabled = true;
            displayMessage("✨ Anda menekan UNO! ✨", 'uno');
            return;
        }
        // call UNO on bot if bot has 1 and hasn't called
        if (botHand.length === 1 && !gameState.unoCalled.bot) {
            // if bot didn't call, penalize bot
            clearUnoTimer('bot');
            displayMessage("🗣️ Anda memanggil UNO pada Bot! Bot mendapat penalti +2.", 'uno');
            drawCards(botHand, 2);
            return;
        }
    }
}

// =======================================================
// ROUND ENDING, BETS, RESTART
// =======================================================
function startRound() {
    const bet = parseInt($betInput.value);
    if (isNaN(bet) || bet < 100) {
        alert("Taruhan minimal adalah $100.", 'error');
        return;
    }
    if (bet > gameState.saldo) {
        alert(`Taruhan melebihi saldo Anda ($${gameState.saldo})!`, 'error');
        return;
    }

    gameState.taruhan = bet;
    $betModal.classList.add('hidden');
    gameState.isBettingPhase = false;

    createDeck();
    dealCards();
    $turnIndicator.textContent = 'Giliran: Pemain';
    displayMessage('Ronde dimulai! Mainkan kartu Anda.');
    updateUI();
}

function endRound(winner) {
    const winAmount = gameState.taruhan;
    if (winner === 'player') {
        gameState.saldo += winAmount;
        displayMessage(`🎉 SELAMAT! Anda menang! Saldo bertambah $${winAmount}.`, 'success');
    } else {
        gameState.saldo -= winAmount;
        displayMessage(`😭 BOT MENANG. Saldo berkurang $${winAmount}.`, 'error');
    }
    updateUI();

    // check game over
    if (gameState.saldo <= 0) {
        document.getElementById('game-over-message').textContent = "GAME OVER! Saldo Anda habis/minus.";
        $gameOverModal.classList.remove('hidden');
        return;
    }

    setTimeout(() => {
        gameState.isBettingPhase = true;
        $betInput.value = Math.min(100, gameState.saldo);
        $betInput.max = gameState.saldo;
        $betModal.classList.remove('hidden');
        displayMessage("Siap untuk ronde berikutnya? Masukkan taruhan.", 'info');
    }, 2000);
}

function restartGame() {
    gameState.saldo = 5000;
    gameState.taruhan = 0;
    $gameOverModal.classList.add('hidden');
    gameState.isBettingPhase = true;
    $betInput.value = 100;
    $betInput.max = 5000;
    $betModal.classList.remove('hidden');
    displayMessage("Selamat datang kembali! Saldo awal $5000.", 'info');
    updateUI();
}

// =======================================================
// UI: update, messages, event wiring
// =======================================================
function updateUI() {
    // Top card
    const top = discardPile[discardPile.length - 1];
    if (top) {
        let src = '';
        if (top.type === 'wild') {
            // Use correct asset names: plus4 -> plus_4.png, wild -> wild.png
            if (top.value === 'plus4') {
                src = 'assets/plus_4.png';
            } else {
                src = 'assets/wild.png';
            }
            // border color set to chosen color
            $topCard.style.borderColor = gameState.currentColor || 'white';
            $topCard.style.filter = `drop-shadow(0 0 10px ${gameState.currentColor || 'white'})`;
        } else {
            src = `assets/${top.color}_${top.value}.png`;
            $topCard.style.borderColor = 'white';
            $topCard.style.filter = 'none';
        }
        $topCard.src = src;
        $topCard.alt = `${top.color} ${top.value}`;
    } else {
        $topCard.src = 'assets/card_back.png';
    }

    // Player hand
    $playerHand.innerHTML = playerHand.map((card, index) => {
        let imgSrc;
        if (card.type === 'wild') {
            imgSrc = card.value === 'plus4' ? 'assets/plus_4.png' : 'assets/wild.png';
        } else {
            imgSrc = `assets/${card.color}_${card.value}.png`;
        }
        const topCard = discardPile[discardPile.length - 1];
        let disabledClass = '';
        if (gameState.turn === 'player' && !checkValidPlay(card, topCard) && card.type !== 'wild') {
            disabledClass = 'disabled';
        }
        return `<img src="${imgSrc}" alt="${card.color} ${card.value}" class="card player-card ${disabledClass}" data-index="${index}" data-color="${card.color}" data-value="${card.value}">`;
    }).join('');

    // Bot hand (show backs)
    let html = `<h3>Bot (<span id="bot-card-count">${botHand.length}</span> Kartu)</h3>`;
    for (let i = 0; i < botHand.length; i++) {
        html += `<img src="assets/card_back.png" alt="Card Back" class="card bot-card">`;
    }
    $botHand.innerHTML = html;

    // saldo & bet
    $saldoDisplay.textContent = gameState.saldo;
    $betDisplay.textContent = gameState.taruhan;

    // UNO button enabled only when player has 1 card and timer active
    $unoBtn.disabled = !(playerHand.length === 1 && gameState.unoTimers.player !== null);

    // color picker visibility is controlled elsewhere
}

// message styling
function displayMessage(message, type = 'info') {
    $gameMessage.textContent = message;
    if (type === 'error') $gameMessage.style.color = 'red';
    else if (type === 'action') $gameMessage.style.color = 'yellow';
    else if (type === 'uno' || type === 'success') $gameMessage.style.color = '#4CAF50';
    else $gameMessage.style.color = 'white';
}

// =======================================================
// EVENTS
// =======================================================
document.addEventListener('DOMContentLoaded', () => {
    $betModal.classList.remove('hidden');
    $betInput.max = gameState.saldo;
    updateUI();
});

// Start round
$startRoundBtn.addEventListener('click', startRound);
$restartGameBtn.addEventListener('click', restartGame);

// Player plays card by clicking
$playerHand.addEventListener('click', (e) => {
    if (gameState.turn !== 'player' || gameState.isBettingPhase) return;
    if (e.target.classList.contains('player-card') && !e.target.classList.contains('disabled')) {
        const idx = parseInt(e.target.dataset.index);
        playCard(idx, false);
    }
});

// Draw card button
$drawCardBtn.addEventListener('click', () => {
    if (gameState.turn !== 'player' || gameState.isBettingPhase) return;

    const topCard = discardPile[discardPile.length - 1];

    // If player has any valid card, disallow draw
    if (playerHand.some(card => checkValidPlay(card, topCard) || card.type === 'wild')) {
        displayMessage("Anda masih punya kartu yang valid. Tidak boleh mengambil kartu.", 'error');
        return;
    }

    // Draw one
    displayMessage("Mengambil satu kartu dari deck...", 'info');
    drawCards(playerHand, 1);

    const newCard = playerHand[playerHand.length - 1];
    if (checkValidPlay(newCard, topCard) || newCard.type === 'wild') {
        // allow player to play it or skip
        gameState.canDrawAndPlay = true;
        $skipDrawBtn.classList.remove('hidden');
        displayMessage("Kartu yang diambil dapat dimainkan. Klik kartu untuk mainkan atau tekan Lewati Giliran.", 'info');
    } else {
        displayMessage("Kartu tidak bisa dimainkan. Giliran dilewati.", 'info');
        setTimeout(() => handleNextTurn(false), 600);
    }
});

// Skip after draw
$skipDrawBtn.addEventListener('click', () => {
    if (gameState.turn === 'player' && gameState.canDrawAndPlay) {
        displayMessage("Giliran dilewati.", 'info');
        gameState.canDrawAndPlay = false;
        $skipDrawBtn.classList.add('hidden');
        handleNextTurn(false);
    }
});

// UNO button
$unoBtn.addEventListener('click', () => {
    handleUnoCall('player');
});

// color picker (for wild played by player)
$colorPicker.addEventListener('click', (e) => {
    if (e.target.classList && e.target.classList.contains('color-btn')) {
        const newColor = e.target.dataset.color;
        gameState.currentColor = newColor;
        $colorPicker.classList.add('hidden');
        displayMessage(`Warna baru dipilih: ${newColor.toUpperCase()}`, 'action');

        // if last card was plus4 by player, apply draw for bot
    const last = discardPile[discardPile.length - 1];
    if (last && last.value === 'plus4') {
            // bot takes 4
            drawCards(botHand, 4);
            // player retains turn per our chosen flow; next is player's turn
            gameState.turn = 'player';
            updateUI();
            return;
        }

        // normal wild - advance turn
        updateUI();
        handleNextTurn(false);
    }
});
