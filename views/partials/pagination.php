<div class="pagination">

<?php if ($page > 1): ?>
    <a href="?page=<?= $page - 1 ?>">Prev</a>
<?php endif; ?>

<?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>">
        <?= $i ?>
    </a>
<?php endfor; ?>

<?php if ($page < $totalPages): ?>
    <a href="?page=<?= $page + 1 ?>">Next</a>
<?php endif; ?>

</div>