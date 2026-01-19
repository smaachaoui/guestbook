<!-- Vue Administration -->
<div class="container-fluid py-4">
    <div class="row g-3">

        <!-- ASIDE ADMIN -->
        <aside class="col-12 col-md-3 col-lg-2">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <strong>Menu admin</strong>
                </div>
                <div class="list-group list-group-flush">
                    <a
                        class="list-group-item list-group-item-action <?= ($section ?? 'dashboard') === 'dashboard' ? 'active' : '' ?>"
                        href="index.php?page=admin&section=dashboard">
                        Tableau de bord
                    </a>

                    <a
                        class="list-group-item list-group-item-action <?= ($section ?? '') === 'users' ? 'active' : '' ?>"
                        href="index.php?page=admin&section=users">
                        Utilisateurs
                    </a>

                    <a
                        class="list-group-item list-group-item-action <?= ($section ?? '') === 'guestbook' ? 'active' : '' ?>"
                        href="index.php?page=admin&section=guestbook">
                        Livre d'or
                    </a>
                </div>
            </div>
        </aside>

        <!-- CONTENU -->
        <section class="col-12 col-md-9 col-lg-10">
            <h1 class="mb-4">Administration</h1>

            <?php $currentSection = $section ?? 'dashboard'; ?>

            <?php include __DIR__ . '/admin/partials/_alerts.php'; ?>

            <?php
                switch ($currentSection) {
                    case 'users':
                        include __DIR__ . '/admin/partials/_users.php';
                        break;

                    case 'guestbook':
                        include __DIR__ . '/admin/partials/_guestbook.php';
                        break;

                    case 'dashboard':
                    default:
                        include __DIR__ . '/admin/partials/_dashboard.php';
                        break;
                }
            ?>
        </section>

    </div>
</div>
