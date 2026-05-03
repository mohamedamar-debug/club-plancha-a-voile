<?php
/**
 * index.php
 * Page d'accueil — Point d'entrée principal
 */
declare(strict_types=1);

require_once __DIR__ . '/config/security.php';

$page_title = 'Accueil';
$current_page = 'index';

require __DIR__ . '/views/layout/header.php';
?>

  <section class="hero" aria-label="Présentation du club">
    <div class="hero-content">
      <div class="hero-badge">⚓ Fondé en 1987 · Archipel de Kerkennah</div>
      <h1>Naviguez avec l'Âme<br/>de la Méditerranée</h1>
      <p>
        Plongez dans la tradition millénaire de la voile kerkennienne.
        Le Club des Felouques vous invite à redécouvrir la mer à travers
        les embarcations en bois qui ont façonné l'identité de notre archipel.
      </p>
      <div class="hero-btns">
        <a href="contact.php" class="btn btn-primary">⚓ Rejoindre le club</a>
        <a href="histoire.html" class="btn btn-secondary">Découvrir l'histoire →</a>
      </div>
    </div>
  </section>

  <main>

    <div class="info-band" role="region" aria-label="Chiffres clés du club">
      <div class="stat">
        <span class="stat-num">37</span>
        <span class="stat-label">Années d'existence</span>
      </div>
      <div class="stat">
        <span class="stat-num">120+</span>
        <span class="stat-label">Membres actifs</span>
      </div>
      <div class="stat">
        <span class="stat-num">18</span>
        <span class="stat-label">Felouques en flotte</span>
      </div>
      <div class="stat">
        <span class="stat-num">45+</span>
        <span class="stat-label">Courses annuelles</span>
      </div>
    </div>

    <section aria-labelledby="titre-presentation">
      <div class="section-title">
        <span class="icon">🌊</span>
        <h2 id="titre-presentation">Le Club en quelques mots</h2>
      </div>

      <div class="highlight-box">
        <h3>Notre Mission</h3>
        <p>
          Le <strong>Club des Felouques de Kerkennah</strong> est une association dédiée à la
          préservation et à la promotion de la navigation traditionnelle dans l'archipel de Kerkennah.
        </p>
        <p>
          Nous perpétuons l'art ancestral de la construction et du pilotage des <em>felouques</em>.
        </p>
        <a href="histoire.html" class="btn btn-mer mt-1">Lire notre histoire complète →</a>
      </div>
    </section>

    <section aria-labelledby="titre-activites">
      <div class="section-title">
        <span class="icon">🏆</span>
        <h2 id="titre-activites">Nos Activités</h2>
      </div>

      <div class="cards-grid">
        <article class="card">
          <div class="card-img">⛵</div>
          <div class="card-body">
            <h3>Courses de Felouques</h3>
            <p>Des régates régulières entre les îles de Kerkennah.</p>
            <a href="calendrier.php" class="btn btn-mer">Voir le calendrier</a>
          </div>
        </article>

        <article class="card">
          <div class="card-img">🔨</div>
          <div class="card-body">
            <h3>Ateliers de Construction</h3>
            <p>Techniques traditionnelles de construction navale.</p>
            <a href="contact.php" class="btn btn-mer">S'inscrire</a>
          </div>
        </article>

        <article class="card">
          <div class="card-img">📚</div>
          <div class="card-body">
            <h3>Formation à la Navigation</h3>
            <p>Des cours pour tous les niveaux de certification.</p>
            <a href="contact.php" class="btn btn-mer">En savoir plus</a>
          </div>
        </article>

        <article class="card">
          <div class="card-img">📸</div>
          <div class="card-body">
            <h3>Événements Culturels</h3>
            <p>Festivals maritimes et expositions photo.</p>
            <a href="galerie.html" class="btn btn-mer">Voir la galerie</a>
          </div>
        </article>
      </div>
    </section>

  </main>

<?php require __DIR__ . '/views/layout/footer.php'; ?>