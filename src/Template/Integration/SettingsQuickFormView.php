<div class="quickapi-wrapper-admin">
    <h1>Настройки</h1>
    <div class="quickapi-group-box">
        <div class="quickapi-field">
            <div class="quickapi-text--label">ID интеграции</div>
            <div class="quickapi-text--copy"><?= $post->ID; ?></div>
        </div>

        <div class="quickapi-field">
            <div class="quickapi-text--label">Секретный ключ</div>
            <div class="quickapi-text--copy"><?= (empty($secret) ? '<red>Сгенерируйте ключ в настройках</red>' : $secret) ?></div>
        </div>

        <div class="quickapi-field">
            <div class="quickapi-text--label">ID формы интеграции</div>
            <div class="quickapi-text--copy"><?= (!empty($currentProjectId->value) ? $currentProjectId->value : 'Форма не выбрана'); ?></div>
        </div>
    </div>

    <div class="quickapi-group-box">
        <?= wp_nonce_field($nonce, $nonce); ?>
        <div class="quickapi-field">
            <label class="quickapi-label" for="<?= $currentProjectId->name; ?>">Форма интеграции</label>
            <select class="quickapi-select" name="<?= $currentProjectId->name; ?>">
                <?php foreach ($projects as $project) : ?>
                    <option value="<?= $project['id']; ?>" <?= ($currentProjectId->value == $project['id'] ? 'selected' : ''); ?>>
                        <?= $project['title']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>