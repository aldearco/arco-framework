<nav>
    <ul class="pagination justify-content-center">
        <li class="page-item <?= $pagination->onFirstPage() ? 'disabled' : '' ?>">
            <?php if ($pagination->onFirstPage()): ?>
            <span class="page-link">Previous</span>
            <?php else: ?>
            <a href="<?= $pagination->previousPageUrl() ?>" class="page-link">Previous</a>
            <?php endif ?>
        </li>
        <?php $showDots = false; ?>
        <?php foreach ($pagination->pagesData() as $number => $page): ?>
            <?php if ($number == $pagination->currentPage() || $number == $pagination->currentPage() - 1 || $number == $pagination->currentPage() + 1 || $number == 1 || $number == $pagination->lastPage()): ?>
                <li class="page-item <?= $page['active'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $page['url'] ?>"><?= $number ?></a>
                </li>
                <?php $showDots = false; ?>
            <?php elseif (!$showDots): ?>
                <?php $showDots = true; ?>
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            <?php endif ?>
        <?php endforeach ?>
        <li class="page-item <?= $pagination->onLastPage() ? 'disabled' : '' ?>">
            <?php if ($pagination->onLastPage()): ?>
            <span class="page-link">Next</span>
            <?php else: ?>
            <a href="<?= $pagination->nextPageUrl() ?>" class="page-link">Next</a>
            <?php endif ?>
        </li>
    </ul>
</nav>