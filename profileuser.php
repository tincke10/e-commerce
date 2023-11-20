<div class="app-sidebar__user">
    <img class="app-sidebar__user-avatar" src="<?php echo $_SESSION['image']; ?>" alt="<?php echo $_SESSION['name']; ?>">
    <div>
        <p class="app-sidebar__user-name"><?php echo $_SESSION['name'] . ' ' . $_SESSION['surname']; ?></p>
        <p class="app-sidebar__user-designation"><?php echo $_SESSION['rol']; ?></p>
    </div>
</div>