window.luckyDraw = () => ({
    isRolling: false,
    rollBatch: [],
    currentIndex: 0,
    rollInterval: null,
    currentDisplay: 'READY',
    currentName: '',
    targetWinnerInvoice: null,

    init() {
        this.initParticles();
        this.initEventListeners();
        this.initKeyboardShortcuts();
    },

    initParticles() {
        const particlesContainer = document.getElementById('particles');
        if (!particlesContainer) return;

        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 20 + 's';
            particle.style.animationDuration = (15 + Math.random() * 10) + 's';
            particlesContainer.appendChild(particle);
        }
    },

    initEventListeners() {
        // Roll started - server confirmed, begin animation
        this.$wire.on('roll-started', (event) => {
            this.rollBatch = event[0].batch;
            this.targetWinnerInvoice = event[0].winner_invoice;
            this.startClientRoll();
        });

        // Winner revealed
        this.$wire.on('winner-revealed', (event) => {
            this.finalizeWinner(event[0].winner);
        });

        // Prize changed - RESET the display
        this.$wire.on('prize-changed', () => {
            this.resetDisplay();
        });
    },

    resetDisplay() {
        this.isRolling = false;
        this.currentDisplay = 'READY';
        this.currentName = '';
        this.rollBatch = [];
        this.targetWinnerInvoice = null;
        if (this.rollInterval) {
            clearTimeout(this.rollInterval);
        }
    },

    initKeyboardShortcuts() {
        const component = this.$el;
        const remaining = parseInt(component.dataset.remaining) || 0;
        const showNext = component.dataset.showNext === 'true';

        document.addEventListener('keydown', (e) => {
            if (e.code === 'Space' && remaining > 0 && !showNext && !this.isRolling && !e.repeat) {
                e.preventDefault();
                this.$wire.startRoll();
            }
            if (e.code === 'Enter' && showNext) {
                e.preventDefault();
                this.$wire.proceedToNextPrize();
            }
        });
    },

    startRoll() {
        // Set rolling state IMMEDIATELY
        this.isRolling = true;
        this.currentDisplay = 'Preparing...';
        this.currentName = '';

        // Call server to get batch and pre-select winner
        this.$wire.startRoll();
    },

    startClientRoll() {
        if (this.rollBatch.length === 0 || !this.targetWinnerInvoice) {
            // Server failed, reset
            this.isRolling = false;
            this.currentDisplay = 'READY';
            return;
        }

        this.currentIndex = 0;
        let step = 0;

        const seconds = 2;
        const speed = 50;
        const maxSteps = (seconds * 1000) / speed;

        // Find target index in batch
        let targetIndex = this.rollBatch.findIndex(item => item.invoice === this.targetWinnerInvoice);
        if (targetIndex === -1) targetIndex = 0;

        const roll = () => {
            // Normal rolling until last few steps
            if (step < maxSteps - 5) {
                this.currentIndex = Math.floor(Math.random() * this.rollBatch.length);
            } else {
                // Last 5 steps: navigate toward target
                const remainingSteps = maxSteps - step;
                if (remainingSteps > 1) {
                    const diff = targetIndex - this.currentIndex;
                    if (diff > 0) {
                        this.currentIndex++;
                    } else if (diff < 0) {
                        this.currentIndex--;
                    }
                } else {
                    this.currentIndex = targetIndex;
                }
            }

            this.currentDisplay = this.rollBatch[this.currentIndex].invoice;
            this.currentName = this.rollBatch[this.currentIndex].winner_name;
            step++;

            if (step < maxSteps) {
                this.rollInterval = setTimeout(roll, speed);
            } else {
                clearTimeout(this.rollInterval);
                this.$wire.revealWinner();
            }
        };

        roll();
    },

    finalizeWinner(winner) {
        this.currentDisplay = winner.invoice_number;
        this.currentName = winner.name;
        this.isRolling = false;
        this.targetWinnerInvoice = null;

        // Add zoom effect class to rolling display
        const rollingDisplay = document.querySelector('.rolling-display');
        if (rollingDisplay) {
            rollingDisplay.classList.add('winner-announced');
            setTimeout(() => rollingDisplay.classList.remove('winner-announced'), 3000);
        }

        this.playWinSound();
        this.triggerConfetti();

        setTimeout(() => {
            const container = document.getElementById('winners-container');
            if (container) {
                container.scrollTo({ top: 0, behavior: 'smooth' });
            }

            const topWinner = document.getElementById('winner-box-0');
            if (topWinner) {
                topWinner.classList.add('highlight-winner');
                setTimeout(() => topWinner.classList.remove('highlight-winner'), 2000);
            }
        }, 100);
    },

    playWinSound() {
        const audio = document.getElementById('win-sound');
        if (audio) {
            audio.currentTime = 0;
            audio.play().catch(() => this.playSynthSound());
        } else {
            this.playSynthSound();
        }
    },

    playSynthSound() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();

            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.setValueAtTime(523.25, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(1046.5, ctx.currentTime + 0.1);
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.6);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.6);

            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.frequency.setValueAtTime(659.25, ctx.currentTime);
            gain2.gain.setValueAtTime(0.2, ctx.currentTime);
            gain2.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.5);
            osc2.start(ctx.currentTime);
            osc2.stop(ctx.currentTime + 0.5);
        } catch(e) {}
    },

    triggerConfetti() {
        const tryConfetti = (attempts = 0) => {
            if (typeof confetti !== 'undefined') {
                const colors = ['#00f3ff', '#bc13fe', '#ffd700', '#ff006e'];

                setTimeout(() => {
                    const explodeEnd = Date.now() + 2000;
                    const explode = () => {
                        confetti({
                            particleCount: 8,
                            spread: 360,
                            origin: { x: 0.5, y: 0.5 },
                            colors: colors,
                            gravity: 0.6,
                            scalar: 1.2,
                            ticks: 150
                        });
                        if (Date.now() < explodeEnd) requestAnimationFrame(explode);
                    };
                    explode();

                    confetti({
                        particleCount: 100,
                        spread: 360,
                        origin: { x: 0.5, y: 0.5 },
                        colors: colors,
                        gravity: 0.4,
                        scalar: 1.5,
                        ticks: 200
                    });
                }, 1000);

            } else if (attempts < 10) {
                setTimeout(() => tryConfetti(attempts + 1), 100);
            } else {
                console.warn('Confetti library failed to load');
            }
        };

        tryConfetti();
    }
});
