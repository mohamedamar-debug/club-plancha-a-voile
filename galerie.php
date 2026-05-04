<?php
/**
 * galerie.php — Front Controller pour la page Galerie Multimédia
 */ 
declare (strict_types=1);
require_once __DIR__ . '/config/security.php';
$page_title = 'Galerie';
$current_page = 'galerie';

require __DIR__ . '/views/layout/header.php';
?>

  <section class="hero" aria-label="En-tête Galerie">
    <div class="hero-content">
      <div class="hero-badge">📸 Multimédia</div>
      <h1>Galerie<br/><em>Mer & Felouques</em></h1>
      <p>Plongez dans l'atmosphère unique de Kerkennah à travers nos photos, vidéos et sons de mer.</p>
    </div>
  </section>

  <main>

    <!-- Vidéo -->
    <section aria-labelledby="titre-video">
      <div class="section-title">
        <span class="icon">🎬</span>
        <h2 id="titre-video">Vidéo : Une Journée en Felouque</h2>
      </div>
      <div class="media-box">
        <h3>🎥  courses de voiliers— Kerkennah 2024</h3>
        <p style="font-size:0.9rem; color:var(--texte-moyen); margin-bottom:1.2rem;">
          Vivez de l'intérieur la course annuelle de kerkennah, avec les équipages du Club des Felouques navigant entre les îles kraten et chergui. Une immersion dans les traditions maritimes de l'archipel.
        </p>
        <video controls width="100%" poster="" aria-label="Vidéo de la régate des felouques de Kerkennah 2024">
          <source src="media/video.mp4" type="video/mp4" />
          <p>Votre navigateur ne supporte pas la lecture vidéo HTML5.
             <a href="media/video.mp4">Télécharger la vidéo</a>.</p>
        </video>
        <p style="font-size:16px;color:var(--texte-moyen);margin-top:0.8rem;">
         
           🎞️ Durée : 1 min 20 sec · Filmé par <a href="https://www.facebook.com/share/v/1CiaLZUm63/" style="color: #ab3598; text-decoration: underline;" target="_blank" >جمعية القراطن للتنمية المستدامة و الثقافة و الترفيه </a> · © Club des Felouques 2024
        
      </p>
      </div>
    </section>

    <!-- Audio -->
    <section aria-labelledby="titre-audio">
      <div class="section-title">
        <span class="icon">🎵</span>
        <h2 id="titre-audio">Ambiances Sonores de Kerkennah</h2>
      </div>

      <div class="media-box">
        <h3>🔊 Chants de Pêcheurs — Tradition Orale de Kerkennah</h3>
        <p style="font-size:0.9rem; color:var(--texte-moyen); margin-bottom:1.2rem;">
          Enregistrement rare des chants traditionnels des pêcheurs de Kerkennah, recueillis en 2024.
        </p>
        <audio controls style="width:100%;" aria-label="Chants traditionnels des pêcheurs de Kerkennah">
          <source src="media/audio1.mp3" type="audio/mpeg" />
          <p>Votre navigateur ne supporte pas la lecture audio HTML5.
             <a href="media/audio1.mp3">Télécharger l'audio</a>.</p>
        </audio>
        <p style="font-size:1rem;color:var(--texte-moyen);margin-top:0.8rem;">
          🎙️ Durée : 1 min 19 sec · Enregistré par <a href="https://www.facebook.com/share/1BB5J6rESA/"
            style="color: #ab3598; text-decoration: underline;"
            target="_blank">
           Tunisia first
          </a> · © Club des Felouques 2024
        </p>
      </div>

      <div class="media-box">
        <h3>🌊 Sons de Mer — Vagues de Kerkennah au Lever du Soleil</h3>
        <p style="font-size:0.9rem; color:var(--texte-moyen); margin-bottom:1.2rem;">
          Une composition sonore des vagues kerkenniennes enregistrée à l'aube sur la plage de la Pointe du Sable.
        </p>
        <audio controls style="width:100%;" aria-label="Sons des vagues de Kerkennah">
                <source src="media/audio2.mp3" type="audio/mpeg" />
                <p>Votre navigateur ne supporte pas la lecture audio HTML5.
             <a href="media/audio2.mp3">Télécharger l'audio</a>.</p>
        </audio>
        <p style="font-size:1rem;color:var(--texte-moyen);margin-top:0.8rem;">
          🌅 Durée : 37 sec · Ambiance naturelle · Libre de droits
        </p>
      </div>
    </section>

    <!-- Galerie photos -->
    <section aria-labelledby="titre-photos">
      <div class="section-title">
        <span class="icon">🖼️</span>
        <h2 id="titre-photos">Galerie Photographique</h2>
      </div>

      <div class="galerie-grid">
        <figure class="galerie-item">
          <div class="galerie-img" ><img src="media/Courses de Felouques.png" alt="Régate du Printemps 2024" /></div>
          <figcaption class="galerie-caption">
            <strong>Régate du Printemps 2024</strong><br/>
            Départ de la grande course annuelle depuis le chargui, avec les équipages du Club des Felouques en pleine action
          </figcaption>
        </figure>

        <figure class="galerie-item">
          <div class="galerie-img" style="background:linear-gradient(135deg,#0a2540,#1a6b8a)" aria-hidden="true"><img src="media/coucher.png" alt="Coucher de Soleil sur Kerkennah" /></div>
          <figcaption class="galerie-caption">
            <strong>Coucher de Soleil sur Kerkennah</strong><br/>
            Vue depuis la felouque  en fin de journée
          </figcaption>
        </figure>

        <figure class="galerie-item">
          <div class="galerie-img" style="background:linear-gradient(135deg,#d4a95a,#3daac7)" aria-hidden="true"><img src="media/hassan.png" alt="Atelier de Construction" /></div>
          <figcaption class="galerie-caption">
            <strong>Atelier de Construction</strong><br/>
            Maître charpentier Hassan Jilani au travail sur une nouvelle felouque
          </figcaption>
        </figure>

        <figure class="galerie-item">
          <div class="galerie-img" style="background:linear-gradient(135deg,#1a6b8a,#5bcfdf)" aria-hidden="true"><img src="media/charfia.png" alt="Pêche à la Charfia" /></div>
          <figcaption class="galerie-caption">
            <strong>Pêche à la Charfia</strong><br/>
            Les palissades de roseaux, patrimoine de l'UNESCO depuis 2020
          </figcaption>
        </figure>

        <figure class="galerie-item">
          <div class="galerie-img" style="background:linear-gradient(135deg,#5bcfdf,#f0e0b8)" aria-hidden="true"><img src="media/trophees.png" alt="Remise des Trophées 2023" /></div>
          <figcaption class="galerie-caption">
            <strong>Championnat tunisien d'aviron de plage 2024</strong><br/>
            مشاركة 9 جمعيات في بطولة تونس للتجديف الشاطئي سرعة لسنة 2024
          </figcaption>
        </figure>

        <figure class="galerie-item">
          <div class="galerie-img" style="background:linear-gradient(135deg,#0a2540,#d4a95a)" aria-hidden="true"><img src="media/festival.png" alt="Festival Maritime 2024" /></div>
          <figcaption class="galerie-caption">
            <strong>Festival Maritime 2024</strong><br/>
            Les familles de Kerkennah célèbrent le patrimoine nautique de l'île
          </figcaption>
        </figure>
      </div>
    </section>

    <!-- Liens vers ressources externes -->
    <section aria-labelledby="titre-liens">
      <div class="section-title">
        <span class="icon">🔗</span>
        <h2 id="titre-liens">Ressources & Liens Utiles</h2>
      </div>
      <div class="highlight-box">
        <ul class="liste-mer">
          <li><a href="https://fr.wikipedia.org/wiki/Kerkennah" target="_blank" rel="noopener" style="color:var(--bleu-mer);">Wikipedia — Archipel de Kerkennah ↗</a></li>
          <li><a href="https://ich.unesco.org" target="_blank" rel="noopener" style="color:var(--bleu-mer);">UNESCO — Patrimoine Culturel Immatériel ↗</a></li>
          <li><a href="https://www.tourisme.gov.tn" target="_blank" rel="noopener" style="color:var(--bleu-mer);">Ministère du Tourisme Tunisien ↗</a></li>
          <li><a href="https://fr.wikipedia.org/wiki/Felouque" target="_blank" rel="noopener" style="color:var(--bleu-mer);">Wikipedia — La Felouque ↗</a></li>
        </ul>
      </div>
    </section>

  </main>

  <?php require __DIR__ . '/views/layout/footer.php'; ?>

