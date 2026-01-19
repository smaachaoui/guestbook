<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-dark text-white">
            <div class="card-body text-center">
                <h3><?= (int)($totalUsers ?? 0) ?></h3>
                <p class="mb-0">Utilisateurs</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-dark text-white">
            <div class="card-body text-center">
                <h3><?= (int)($totalGuestbookMessages ?? 0) ?></h3>
                <p class="mb-0">Messages (livre d'or)</p>
                <small>
                    Visibles: <?= (int)($totalGuestbookVisible ?? 0) ?> /
                    Masqués: <?= (int)($totalGuestbookHidden ?? 0) ?>
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-dark text-white">
            <div class="card-body text-center">
                <h3><?= (int)($newUsers ?? 0) ?></h3>
                <p class="mb-0">Nouveaux (7j)</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Raccourcis</h5>
    </div>
    <div class="card-body d-flex gap-2 flex-wrap">
        <a class="btn btn-outline-dark" href="index.php?page=admin&section=users">Gérer les utilisateurs</a>
        <a class="btn btn-outline-dark" href="index.php?page=admin&section=guestbook">Gérer le livre d'or</a>
    </div>
</div>
