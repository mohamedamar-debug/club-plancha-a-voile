<?php
/**
 * views/contact/form.php
 * Vue — Formulaire d'inscription dynamique
 *
 * Variables fournies par ContactController :
 *   $errors  array   Erreurs de validation ['champ' => 'message']
 *   $old     array   Anciennes valeurs pour re-remplir le formulaire
 */
declare(strict_types=1);

$page_title   = 'Contact & Inscription';
$current_page = 'contact';

require __DIR__ . '/../layout/header.php';

// Helpers locaux
$err = fn(string $key): string => isset($errors[$key])
    ? '<span class="error-msg visible">' . h($errors[$key]) . '</span>'
    : '';

$old_val = fn(string $key): string => h($old[$key] ?? '');

$is_checked_radio = fn(string $name, string $val): string
    => isset($old[$name]) && $old[$name] === $val ? ' checked' : '';

$is_checked_box = fn(string $name, string $val): string
    => isset($old[$name]) && in_array($val, (array)$old[$name], true) ? ' checked' : '';
?>

  <!-- ══════════════════ HERO ══════════════════ -->
  <section class="hero" aria-label="En-tête de page Contact">
    <div class="hero-content">
      <div class="hero-badge">⚓ Rejoignez-nous</div>
      <h1>Inscription au Club</h1>
      <p>
        Complétez le formulaire ci-dessous pour rejoindre le Club des Felouques de Kerkennah.
        Un membre du bureau vous contactera sous 48h pour finaliser votre adhésion.
      </p>
    </div>
  </section>

  <!-- ══════════════════ MAIN ══════════════════ -->
  <main>
    <div style="display: flex; flex-direction: row; gap: 1rem; align-items: flex-start; width: 100%; max-width: 1200px; margin: 0 auto; padding: 20px;">      <!-- ── Formulaire ─────────────────────────────────── -->
      <section style="flex:2; min-width: 400px;" aria-labelledby="titre-form">
        <div class="section-title">
          <span class="icon">📋</span>
          <h2 id="titre-form">Formulaire d'Inscription</h2>
        </div>

        <div class="form-card">

          <!--
            ACTION → contact.php (le même front-controller gère GET et POST)
            METHOD → POST (jamais GET pour les données personnelles)
            NOVALIDATE → on gère la validation côté serveur + JS
          -->
          <form id="form-inscription"action="contact.php" method="POST" novalidate>

           <!-- ── Section 1 : Informations personnelles ── -->
            <div class="form-section-title">👤 Informations Personnelles</div>

            <div class="form-row">
              <div class="form-group <?= isset($errors['prenom']) ? 'field-error' : '' ?>">
                <label for="prenom">Prénom <span class="required-star">*</span></label>
                <input type="text" id="prenom" name="prenom"
                       value="<?= $old_val('prenom') ?>"
                       placeholder="Ex : Ahmed"
                       autocomplete="given-name"
                       required aria-required="true" maxlength="80" />
                <?= $err('prenom') ?>
              </div>
              <div class="form-group <?= isset($errors['nom']) ? 'field-error' : '' ?>">
                <label for="nom">Nom <span class="required-star">*</span></label>
                <input type="text" id="nom" name="nom"
                       value="<?= $old_val('nom') ?>"
                       placeholder="Ex : Ben Salah"
                       autocomplete="family-name"
                       required aria-required="true" maxlength="80" />
                <?= $err('nom') ?>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group <?= isset($errors['email']) ? 'field-error' : '' ?>">
                <label for="email">Adresse e-mail <span class="required-star">*</span></label>
                <input type="email" id="email" name="email"
                       value="<?= $old_val('email') ?>"
                       placeholder="prenom.nom@exemple.tn"
                       autocomplete="email"
                       required aria-required="true" maxlength="180" />
                <?= $err('email') ?>
              </div>
              <div class="form-group <?= isset($errors['telephone']) ? 'field-error' : '' ?>">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone"
                       value="<?= $old_val('telephone') ?>"
                       placeholder="Ex : 74 123 456"
                       autocomplete="tel" maxlength="20" />
                <?= $err('telephone') ?>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group <?= isset($errors['naissance']) ? 'field-error' : '' ?>">
                <label for="naissance">Date de naissance <span class="required-star">*</span></label>
                <input type="date" id="naissance" name="naissance"
                       value="<?= $old_val('naissance') ?>"
                       required aria-required="true"
                       max="<?= date('Y-m-d') ?>"
                       min="<?= date('Y-m-d', strtotime('-120 years')) ?>" />
                <?= $err('naissance') ?>
              </div>
              <div class="form-group">
                <label for="nationalite">Nationalité</label>
                <input type="text" id="nationalite" name="nationalite"
                       value="<?= $old_val('nationalite') ?>"
                       placeholder="Ex : Tunisienne" maxlength="60" />
              </div>
            </div>

            <!-- ── Section 2 : Genre ── -->
            <div class="form-section-title">⚥ Genre <span class="required-star">*</span></div>
            <div class="form-group full <?= isset($errors['genre']) ? 'field-error' : '' ?>" id="wrap-genre">
              <div class="radio-group horizontal" role="radiogroup" aria-required="true">
                <label class="radio-option">
                  <input type="radio" name="genre" value="homme"<?= $is_checked_radio('genre','homme') ?> />
                  <span>👨 Homme</span>
                </label>
                <label class="radio-option">
                  <input type="radio" name="genre" value="femme"<?= $is_checked_radio('genre','femme') ?> />
                  <span>👩 Femme</span>
                </label>
               
              </div>
              <?= $err('genre') ?>
            </div>

            <!-- ── Section 3 : Niveau de navigation ── -->
            <div class="form-section-title">⚓ Niveau de Navigation <span class="required-star">*</span></div>
            <div class="form-group full <?= isset($errors['niveau']) ? 'field-error' : '' ?>" id="wrap-niveau">
              <div class="radio-group" role="radiogroup" aria-required="true">
                <?php
                $niveaux = [
                  'debutant'      => '🌱 Débutant — Aucune expérience en voile traditionnelle',
                  'intermediaire' => '⛵ Intermédiaire — Quelques sorties en mer',
                  'confirme'      => '🏆 Confirmé — Navigation régulière, participation à des régates',
                  'expert'        => '🌟 Expert — Skipper ou instructeur expérimenté',
                ];
                foreach ($niveaux as $val => $label): ?>
                  <label class="radio-option">
                    <input type="radio" name="niveau" value="<?= h($val) ?>"<?= $is_checked_radio('niveau', $val) ?> />
                    <span><?= h($label) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
              <?= $err('niveau') ?>
            </div>

            <!-- ── Section 4 : Activités ── -->
            <div class="form-section-title">🎯 Activités Souhaitées <span class="required-star">*</span></div>
            <p style="font-size:0.85rem; color:var(--texte-moyen); margin-bottom:1rem;">
              Sélectionnez au moins une activité qui vous intéresse :
            </p>
            <div class="form-group full <?= isset($errors['activites']) ? 'field-error' : '' ?>" id="wrap-activites">
              <div class="checkbox-group" role="group" aria-labelledby="label-activites">
                <?php
                $activites_list = [
                  'courses'      => '⛵ Participation aux courses et régates',
                  'formation'    => '📚 Formation à la navigation à la voile',
                  'construction' => '🔨 Atelier construction et restauration de felouques',
                  'plongee'      => '🤿 Sorties snorkeling et découverte du milieu marin',
                  'culture'      => '🎨 Événements culturels et patrimoine maritime',
                  'benevole'     => '🤝 Bénévolat et animation du club',
                ];
                foreach ($activites_list as $val => $label): ?>
                  <label class="check-option">
                    <input type="checkbox" name="activites[]" value="<?= h($val) ?>"<?= $is_checked_box('activites', $val) ?> />
                    <span><?= h($label) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
              <?= $err('activites') ?>
            </div>

            <!-- ── Section 5 : Disponibilités ── -->
            <div class="form-section-title">📅 Disponibilités</div>
            <div class="form-group full"  id="wrap-dispo">
              <div class="checkbox-group horizontal" role="group"
                   style="flex-direction:row; flex-wrap:wrap; gap:0.8rem;">
                <?php
                $dispos = [
                  'semaine'  => '🗓️ En semaine',
                  'weekend'  => '🌅 Week-end',
                  'vacances' => '☀️ Vacances scolaires',
                  'flexible' => '✅ Flexible',
                ];
                foreach ($dispos as $val => $label): ?>
                  <label class="check-option">
                  <input type="checkbox" name="dispo[]" value="<?= h($val) ?>"<?= $is_checked_box('dispo', $val) ?> />                   
                  <span><?= h($label) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- ── Section 6 : Informations complémentaires ── -->
            <div class="form-section-title">💬 Message & Informations Complémentaires</div>

            <div class="form-group full">
              <label for="message">Message libre</label>
              <textarea id="message" name="message" rows="4"
                placeholder="Parlez-nous de vous, de vos motivations ou posez-nous vos questions…"
              ><?= $old_val('message') ?></textarea>
            </div>

            <div class="form-group full">
              <label for="comment">Comment avez-vous entendu parler de nous ?</label>
              <select id="comment" name="comment">
                <option value="" disabled <?= empty($old['source_info']) ? 'selected' : '' ?>>— Choisissez une option —</option>
                <?php
                $sources = [
                  'bouche'    => 'Bouche à oreille',
                  'reseaux'   => 'Réseaux sociaux',
                  'presse'    => 'Presse locale',
                  'evenement' => 'Lors d\'un événement nautique',
                  'internet'  => 'Recherche internet',
                  'autre'     => 'Autre',
                ];
                foreach ($sources as $val => $label):
                    $selected = isset($old['source_info']) && $old['source_info'] === $val ? ' selected' : '';
                ?>
                  <option value="<?= h($val) ?>"<?= $selected ?>><?= h($label) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- ── Règlement ── -->
            <div class="form-section-title">📜 Acceptation du Règlement</div>
            <div class="form-group full <?= isset($errors['reglement']) ? 'field-error' : '' ?>" id="reglement-wrap">
              <label class="check-option" style="border-color: var(--bleu-clair);">
                <input type="checkbox" id="reglement" name="reglement" value="oui"
                       required aria-required="true"
                       <?= !empty($old['reglement']) ? 'checked' : '' ?> />
                <span>
                  J'ai lu et j'accepte le
                  <a href="#" style="color: var(--bleu-mer); text-decoration: underline;">règlement intérieur</a>
                  du Club des Felouques de Kerkennah. <strong>*</strong>
                </span>
              </label>
              <?= $err('reglement') ?>
            </div>

            <div class="form-group full" id="cgu-wrap">
              <label class="check-option">
                <input type="checkbox" id="newsletter" name="newsletter" value="oui"
                       <?= !empty($old['newsletter']) ? 'checked' : '' ?> />
                <span>Je souhaite recevoir les actualités du club par e-mail.</span>
              </label>
            </div>

            <!-- ── Boutons ── -->
            <div class="form-submit-row">
              <p style="font-size:0.8rem; color:var(--texte-moyen);">
                <span class="required-star">*</span> Champs obligatoires
              </p>
              <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                <button type="reset" class="btn btn-secondary"
                        style="border-color:var(--sable-clair); color:var(--texte-moyen);">
                  🔄 Réinitialiser
                </button>
                <button type="submit" class="btn btn-primary">
                  ⚓ Envoyer ma demande d'inscription
                </button>
              </div>
            </div>

          </form>
        </div><!-- /.form-card -->
      </section>

      <!-- ── Colonne latérale ── -->
      <aside style="flex:1; min-width:200px;" aria-label="Informations pratiques">
        <div class="section-title">
          <span class="icon">ℹ️</span>
          <h2>Infos Pratiques</h2>
        </div>
        <div class="highlight-box" style="margin-bottom:1.5rem;">
          <h3>📍 Où nous trouver ?</h3>
          <p style="font-size:0.92rem; margin-top:0.8rem;">
            <strong>Port de Sidi Fredj</strong><br/>
            Île de Chergui, Kerkennah<br/>
            Gouvernorat de Sfax, Tunisie
          </p>
          <p style="font-size:0.82rem; color:var(--texte-moyen);">
            Accès par ferry depuis le port de Sfax (40 min).
          </p>
          <a href="https://maps.app.goo.gl/cz5ELscmej6GsAmdA" target="_blank" rel="noopener" class="btn btn-mer mt-1" style="font-size:0.88rem; padding:0.6rem 1.2rem;">
            📌 Voir sur la carte ↗
          </a>
        </div>
        <div class="highlight-box" style="margin-bottom:1.5rem;">
          <h3>📞 Nous Contacter</h3>
          <ul class="liste-mer" style="margin-top:0.8rem;">
            <li>Tél : +216 74 223 121</li>
            <li>E-mail : planche_a_voile_club@felouques-kerkennah.tn</li>
            <li>Permanence : Mar–Dim, 9h–18h</li>
          </ul>
        </div>
        <div class="highlight-box">
          <h3>💰 Cotisations 2025</h3>
          <ul style="list-style:none; margin-top:0.8rem;">
            <li style="padding:0.5rem 0; border-bottom:1px solid var(--sable-clair); font-size:0.9rem;">
              <strong>Enfant (7–16 ans)</strong><br/>
              <span style="color:var(--bleu-mer); font-size:1.1rem; font-weight:700;">60 DT</span> / an
            </li>
            <li style="padding:0.5rem 0; border-bottom:1px solid var(--sable-clair); font-size:0.9rem;">
              <strong>Junior (17–25 ans)</strong><br/>
              <span style="color:var(--bleu-mer); font-size:1.1rem; font-weight:700;">90 DT</span> / an
            </li>
            <li style="padding:0.5rem 0; font-size:0.9rem;">
              <strong>Adulte (26 ans+)</strong><br/>
              <span style="color:var(--bleu-mer); font-size:1.1rem; font-weight:700;">150 DT</span> / an
            </li>
          </ul>
        </div>
      </aside>

    </div>
  </main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
