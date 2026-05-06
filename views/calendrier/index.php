<?php
/**
 * views/calendrier/index.php
 * Vue — Calendrier des courses (données dynamiques depuis BDD)
 *
 * Variables fournies par CalendrierController :
 *   $evenements  array  Tableau de tous les événements
 */
declare(strict_types=1);

$page_title   = 'Calendrier des Courses';
$current_page = 'calendrier';

require __DIR__ . '/../layout/header.php';
?>

  <section class="hero" aria-label="En-tête Calendrier">
    <div class="hero-content">
      <div class="hero-badge">📅 Saison 2025</div>
      <h1>Calendrier<br/><em>des Courses & Régates</em></h1>
      <p>Toutes les dates des compétitions, sorties en mer et événements du club pour la saison 2025.</p>
      <a href="contact.php" class="btn btn-primary">⚓ S'inscrire à une épreuve</a>
    </div>
  </section>

  <main>

    <?php
    // ── Statistiques dynamiques depuis la BDD ────────────────────────────
    $evenements = $evenements ?? [];      
    $total       = count($evenements);
    $nb_ouverts  = count(array_filter($evenements, fn($e) => $e['statut'] === 'ouvert'));
    $nb_complets = count(array_filter($evenements, fn($e) => $e['statut'] === 'complet'));
    ?>
    <div class="info-band">
      <div class="stat">
        <span class="stat-num"><?= $total ?></span>
        <span class="stat-label">Courses programmées</span>
      </div>
      <div class="stat">
        <span class="stat-num"><?= $nb_ouverts ?></span>
        <span class="stat-label">Inscriptions ouvertes</span>
      </div>
      <div class="stat">
        <span class="stat-num"><?= $nb_complets ?></span>
        <span class="stat-label">Épreuves complètes</span>
      </div>
      <div class="stat">
        <span class="stat-num">Mai–Oct</span>
        <span class="stat-label">Saison principale</span>
      </div>
    </div>

    <!-- Tableau complet des courses (données BDD) -->
    <section aria-labelledby="titre-tableau">
      <div class="section-title">
        <span class="icon">🗓️</span>
        <h2 id="titre-tableau">Programme Complet des Épreuves 2025</h2>
      </div>

      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Nom de l'Épreuve</th>
              <th scope="col">Date</th>
              <th scope="col">Départ</th>
              <th scope="col">Parcours</th>
              <th scope="col">Distance</th>
              <th scope="col">Catégorie</th>
              <th scope="col">Inscriptions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($evenements)): ?>
              <tr>
                <td colspan="8" style="text-align:center; padding:2rem; color:var(--texte-moyen);">
                  Aucun événement programmé pour le moment.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($evenements as $index => $e):
                  $badge   = Evenement::badgeInfo($e['statut']);
                  $dateStr = Evenement::formatDate($e['date_debut']);
                  // Épreuve multi-jours
                  if (!empty($e['date_fin']) && $e['date_fin'] !== $e['date_debut']) {
                      $dateStr .= ' – ' . Evenement::formatDate($e['date_fin']);
                  }
                  $heure = substr($e['heure_depart'], 0, 5); // "HH:MM"
              ?>
                <tr>
                  <td><?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) ?></td>
                  <td><?= h($e['nom_epreuve']) ?></td>
                  <td><?= h($dateStr) ?></td>
                  <td><?= h($heure) ?></td>
                  <td><?= h($e['parcours']) ?></td>
                  <td><?= $e['distance_mn'] ? h($e['distance_mn']) . ' mn' : '—' ?></td>
                  <td><?= h($e['categorie']) ?></td>
                  <td>
                    <span class="badge <?= h($badge['class']) ?>">
                      <?= h($badge['label']) ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <p style="font-size:0.85rem; color:var(--texte-moyen); margin-top:1.2rem; padding-left:0.5rem;">
        <strong>mn</strong> = milles nautiques · Les horaires et parcours peuvent être modifiés selon les conditions météorologiques.
        <a href="contact.php" style="color:var(--bleu-mer);">Contactez-nous</a> pour toute question.
      </p>
    </section>

    <!-- Tableau des catégories -->
    <section aria-labelledby="titre-categories">
      <div class="section-title">
        <span class="icon">🏅</span>
        <h2 id="titre-categories">Catégories de Compétition</h2>
      </div>
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th scope="col">Catégorie</th>
              <th scope="col">Âge</th>
              <th scope="col">Niveau requis</th>
              <th scope="col">Embarcation</th>
              <th scope="col">Licence obligatoire</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $categories = [
              ['Poussins',  '7 – 11 ans',   'Initiation encadrée',           'Felouquette (4 m)',            'Oui — Licence Jeune'],
              ['Benjamins', '12 – 15 ans',  'Débutant à intermédiaire',      'Felouque standard (6 m)',      'Oui — Licence Jeune'],
              ['Juniors',   '16 – 25 ans',  'Intermédiaire',                 'Felouque standard (6–8 m)',    'Oui — Licence Junior'],
              ['Séniors',   '26 – 59 ans',  'Confirmé à Expert',             'Felouque de compétition (8–10 m)', 'Oui — Licence Sénior'],
              ['Vétérans',  '60 ans et +',  'Confirmé',                      'Felouque de compétition (8–10 m)', 'Oui — Licence Vétéran'],
              ['Tradition', 'Tous âges',    'Confirmé (felouque ancienne)',   'Felouque classique (avant 1980)', 'Oui — Licence Tradition'],
            ];
            foreach ($categories as $cat): ?>
              <tr>
                <?php foreach ($cat as $cell): ?>
                  <td><?= h($cell) ?></td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section>
      <div class="highlight-box text-center">
        <h3>Prêt à concourir cette saison ?</h3>
        <p style="max-width:500px; margin:0.8rem auto 1.5rem;">
          Inscrivez-vous dès maintenant pour garantir votre place dans les épreuves de votre choix.
        </p>
        <a href="contact.php" class="btn btn-primary">⚓ Formulaire d'inscription →</a>
      </div>
    </section>

  </main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
