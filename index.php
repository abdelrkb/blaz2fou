<?php include_once('head.php'); ?>
<link rel="stylesheet" href="style/leaderboard.css">

<div class="feed-container">

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-fire"></i>Blaze2Fou</h1>
        <p>Découvrez tous les blazes de tarté de la communauté</p>
    </div>

    <div class="add-blaze-wrapper">
        <button id="toggleAddBtn" class="add-btn">Ajouter</button>

        <form id="addBlazeForm" class="add-form">
            <input type="text" id="newBlazeInput" placeholder="Entre ton blaze là" class="add-input" />
            <button type="submit" class="add-submit"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="loading-spinner">
        <div class="spinner"></div>
    </div>

    <!-- Blaze Feed -->
    <div id="blazeFeed" class="blaze-feed" style="display: none;"></div>

    <!-- Empty State -->
    <div id="emptyState" class="empty-state" style="display: none;">
        <i class="fas fa-fire-alt"></i>
        <h3>Aucun blaze trouvé</h3>
        <p>Soyez le premier à partager votre blaze !</p>
    </div>

    <!-- Load More Button -->
    <button id="loadMoreBtn" class="load-more-btn" style="display: none;">
        <i class="fas fa-plus"></i> Charger plus de blazes
    </button>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    let currentPage = 1;
    let isLoading = false;
    let allBlazes = [];
    let filteredBlazes = [];
    let currentSort = 'date';
    let currentOrder = 'desc';

    function loadBlazes(page = 1, append = false) {
        if (isLoading) return;
        isLoading = true;

        if (!append) {
            $('#loadingSpinner').show();
            $('#blazeFeed').hide();
            $('#emptyState').hide();
            $('#loadMoreBtn').hide();
        }

        $.ajax({
            url: 'load_all_blazes.php',
            method: 'GET',
            dataType: 'json',
            data: { page, sort: currentSort, order: currentOrder },
            success: function (blazes) {
                if (page === 1) {
                    allBlazes = blazes;
                    filteredBlazes = blazes;
                } else {
                    allBlazes = allBlazes.concat(blazes);
                    filteredBlazes = filteredBlazes.concat(blazes);
                }

                displayBlazes(append);
                $('#loadMoreBtn').toggle(blazes.length === 20);
            },
            error: function () {
                console.error("Erreur lors du chargement.");
            },
            complete: function () {
                isLoading = false;
                $('#loadingSpinner').hide();
            }
        });
    }

    function displayBlazes(append = false) {
        const feed = $('#blazeFeed');
        if (!append) feed.empty();

        if (filteredBlazes.length === 0) {
            $('#emptyState').show();
            $('#blazeFeed').hide();
            return;
        }

        $('#emptyState').hide();
        $('#blazeFeed').show();

        const startIndex = append ? feed.children().length : 0;
        const blazesToShow = filteredBlazes.slice(startIndex);

        blazesToShow.forEach((blaze, index) => {
            const rating = parseFloat(blaze.note) || 0;
            const userNote = parseInt(blaze.user_note) || 0;
            const userInitials = blaze.user.substring(0, 2).toUpperCase();

            const ratingEmojis = ['1️⃣','2️⃣','3️⃣','4️⃣','5️⃣','6️⃣','7️⃣','8️⃣','9️⃣','🔟'];
            const card = $(`
                <div class="blaze-card" style="animation-delay: ${index * 0.1}s">
                    <div class="card-header">
                        <div class="user-info">
                            <div class="user-avatar">${userInitials}</div>
                            <div class="user-details">
                                <h3>${escapeHtml(blaze.user)}</h3>
                                <p><i class="fas fa-fire"></i> Blazeur</p>
                            </div>
                        </div>
                        <div class="card-date"><i class="fas fa-calendar-alt"></i> ${formatDate(blaze.date)}</div>
                    </div>
                    <div class="card-body">
                        <div class="blaze-content">
                            <div class="blaze-text">
                                <span class="blaze-name">${escapeHtml(blaze.blaze)}</span>
                                <!-- From Uiverse.io by Galahhad --> 
                                <button class="copy">
                                <span data-text-end="Copié !" data-text-initial="Copier le blaze" class="tooltip"></span>
                                <span>
                                    <svg xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 6.35 6.35" y="0" x="0" height="20" width="20" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg" class="clipboard">
                                    <g>
                                        <path fill="currentColor" d="M2.43.265c-.3 0-.548.236-.573.53h-.328a.74.74 0 0 0-.735.734v3.822a.74.74 0 0 0 .735.734H4.82a.74.74 0 0 0 .735-.734V1.529a.74.74 0 0 0-.735-.735h-.328a.58.58 0 0 0-.573-.53zm0 .529h1.49c.032 0 .049.017.049.049v.431c0 .032-.017.049-.049.049H2.43c-.032 0-.05-.017-.05-.049V.843c0-.032.018-.05.05-.05zm-.901.53h.328c.026.292.274.528.573.528h1.49a.58.58 0 0 0 .573-.529h.328a.2.2 0 0 1 .206.206v3.822a.2.2 0 0 1-.206.205H1.53a.2.2 0 0 1-.206-.205V1.529a.2.2 0 0 1 .206-.206z"></path>
                                    </g>
                                    </svg>
                                    <svg xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 24 24" y="0" x="0" height="18" width="18" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg" class="checkmark">
                                    <g>
                                        <path data-original="#000000" fill="currentColor" d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z"></path>
                                    </g>
                                    </svg>
                                </span>
                                </button>
                            </div>
                            <div class="rate-interactive" data-id="${blaze.id}" data-note="${userNote}">
                                <button class="emoji-toggle-btn">${rating.toFixed(1)}</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="emoji-choices-footer" data-id="${blaze.id}">
                            ${ratingEmojis.map((emoji, i) => {
                                const note = i + 1;
                                const isGrayed = userNote !== note;
                                return `<div class="emoji-option ${isGrayed ? 'grayed' : ''}" data-value="${note}">${emoji}</div>`;
                            }).join('')}
                        </div>
                    </div>
                </div>
            `);

            feed.append(card);
        });
    }

    function escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = Math.floor((now - date) / (1000 * 60 * 60 * 24));

        if (diff === 0) return "Aujourd'hui";
        if (diff === 1) return "Hier";
        if (diff < 7) return `Il y a ${diff} jours`;
        return date.toLocaleDateString('fr-FR');
    }

    $('#searchInput').on('input', function () {
        const term = $(this).val().toLowerCase();
        filteredBlazes = term === '' ? allBlazes : allBlazes.filter(b =>
            b.blaze.toLowerCase().includes(term) || b.user.toLowerCase().includes(term)
        );
        displayBlazes();
    });

    $('.sort-btn').on('click', function () {
        $('.sort-btn').removeClass('active');
        $(this).addClass('active');
        currentSort = $(this).data('sort');
        currentOrder = $(this).data('order');
        loadBlazes(1, false);
    });

    $('#refreshBtn').on('click', () => loadBlazes(1, false));
    $('#loadMoreBtn').on('click', () => loadBlazes(++currentPage, true));

    $(document).on('click', '.emoji-choices-footer .emoji-option', function () {
        const selected = $(this).data('value');
        const container = $(this).closest('.emoji-choices-footer');
        const id = container.data('id');
        const allEmojis = container.find('.emoji-option');

        if (!$(this).hasClass('grayed')) return;

        $.post('submit_note.php', { blaze_id: id, note: selected }, function (response) {
            if (response.success) {
                allEmojis.each(function () {
                    $(this).toggleClass('grayed', $(this).data('value') !== selected);
                });

                const btn = $(`.rate-interactive[data-id="${id}"] .emoji-toggle-btn`);
                btn.text(parseFloat(response.new_average).toFixed(1));
            } else {
                console.log("Erreur:", response.error);
            }
        }, 'json');
    });

    $('#toggleAddBtn').on('click', function () {
        $('#toggleAddBtn').hide();
        $('#addBlazeForm').css('display', 'flex').show().find('#newBlazeInput').focus();
        });

        $('#addBlazeForm').on('submit', function (e) {
        e.preventDefault();
        const newBlaze = $('#newBlazeInput').val().trim();
        if (newBlaze === '') return;

        $.post('submit_blaze.php', { blaze: newBlaze }, function (response) {
            if (response.success) {
                $('#newBlazeInput').val('');
                $('#addBlazeForm').hide();
                $('#toggleAddBtn').show();
                loadBlazes(1, false);
            } else {
                let msg = "Erreur : ";
                switch (response.error) {
                    case "non_connecté":
                        msg += "Connecte-toi d’abord.";
                        break;
                    case "Mot interdit détecté.":
                    case "Ce blaze existe déjà.":
                    case "Erreur à l’insertion":
                        msg += response.error;
                        break;
                    default:
                        msg += "Une erreur est survenue.";
                }
                alert(msg);
            }
        }, 'json');
    });

    // Copier le blaze au clic
    $(document).on('click', '.copy', function () {
        const blazeText = $(this).closest('.blaze-content').find('.blaze-text').text().trim().replace(/["]/g, '');
        
        navigator.clipboard.writeText(blazeText).then(() => {
            const tooltip = $(this).find('.tooltip');
        }).catch(() => {
            alert("Erreur lors de la copie du blaze.");
        });
    });




    loadBlazes();
});
</script>

<script src="https://kit.fontawesome.com/f9983d149e.js" crossorigin="anonymous"></script>
</body>
</html>
