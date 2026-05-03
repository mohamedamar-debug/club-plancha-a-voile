/**
 * public/script.js
 * Validation côté client — Club des Felouques de Kerkennah
 *
 * Stratégie : validation en temps réel (blur) + validation globale avant envoi.
 * La validation serveur reste la référence de sécurité — ce script améliore
 * uniquement l'expérience utilisateur (UX).
 */

'use strict';

// ─────────────────────────────────────────────────────────────────────────────
// UTILITAIRES
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Affiche ou masque un message d'erreur pour un champ.
 * @param {HTMLElement} field    - L'élément input/select/textarea
 * @param {string|null} message  - Message d'erreur, ou null pour effacer
 */
function setError(field, message) {
  // On cherche le .error-msg frère le plus proche
  const wrap = field.closest('.form-group') || field.closest('[id^="wrap-"]');
  if (!wrap) return;

  const errSpan = wrap.querySelector('.error-msg');

  if (message) {
    if (errSpan) {
      errSpan.textContent = message;
      errSpan.classList.add('visible');
    }
    wrap.classList.add('field-error');
    field.setAttribute('aria-invalid', 'true');
  } else {
    if (errSpan) {
      errSpan.textContent = '';
      errSpan.classList.remove('visible');
    }
    wrap.classList.remove('field-error');
    field.removeAttribute('aria-invalid');
  }
}

/**
 * Affiche l'alerte globale du formulaire.
 * @param {'error'|'success'} type
 * @param {string} message
 */
function showAlert(type, message) {
  const alert = document.getElementById('form-alert');
  if (!alert) return;
  alert.className = type;
  alert.style.display = 'flex';
  alert.textContent = message;
  alert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function hideAlert() {
  const alert = document.getElementById('form-alert');
  if (alert) { alert.style.display = 'none'; alert.className = ''; }
}

// ─────────────────────────────────────────────────────────────────────────────
// RÈGLES DE VALIDATION
// ─────────────────────────────────────────────────────────────────────────────

const RULES = {
  prenom(val) {
    if (!val) return 'Le prénom est obligatoire.';
    if (val.length < 2) return 'Le prénom doit contenir au moins 2 caractères.';
    if (val.length > 80) return 'Le prénom ne peut pas dépasser 80 caractères.';
    return null;
  },
  nom(val) {
    if (!val) return 'Le nom est obligatoire.';
    if (val.length < 2) return 'Le nom doit contenir au moins 2 caractères.';
    if (val.length > 80) return 'Le nom ne peut pas dépasser 80 caractères.';
    return null;
  },
  email(val) {
    if (!val) return "L'adresse e-mail est obligatoire.";
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!re.test(val)) return 'Veuillez saisir une adresse e-mail valide.';
    return null;
  },
  telephone(val) {
    if (!val) return null; // Facultatif
    const re = /^(\+?216|00216)?[2-9]\d{7}$/;
    if (!re.test(val.replace(/\s/g, ''))) {
      return 'Format invalide (ex : 74 123 456 ou +21674123456).';
    }
    return null;
  },
  naissance(val) {
    if (!val) return 'La date de naissance est obligatoire.';
    const birth = new Date(val);
    const today = new Date();
    if (isNaN(birth.getTime()) || birth > today) return 'Veuillez saisir une date valide.';
    const age = Math.floor((today - birth) / (365.25 * 24 * 3600 * 1000));
    if (age < 7)   return "L'âge minimum pour adhérer est de 7 ans.";
    if (age > 120) return 'Veuillez saisir une date valide.';
    return null;
  },
};

// ─────────────────────────────────────────────────────────────────────────────
// INITIALISATION
// ─────────────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('form-inscription');
  if (!form) return; // Ne s'exécute que sur la page contact

  // ── Validation en temps réel (blur) ──────────────────────────────────────
  ['prenom', 'nom', 'email', 'telephone', 'naissance'].forEach(id => {
    const field = document.getElementById(id);
    if (!field || !RULES[id]) return;

    field.addEventListener('blur', () => {
      setError(field, RULES[id](field.value.trim()));
    });

    field.addEventListener('input', () => {
      if (field.getAttribute('aria-invalid') === 'true') {
        setError(field, RULES[id](field.value.trim()));
      }
    });
  });

  // ── Suivi visuel des boutons radio ────────────────────────────────────────
  document.querySelectorAll('.radio-option input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', () => {
      // Efface l'erreur du groupe quand une option est choisie
      const group = radio.closest('[id^="wrap-"]');
      if (group) {
        const errSpan = group.querySelector('.error-msg');
        if (errSpan) { errSpan.textContent = ''; errSpan.classList.remove('visible'); }
        group.classList.remove('field-error');
      }
      // Highlight l'option sélectionnée
      radio.closest('.radio-group')?.querySelectorAll('.radio-option').forEach(opt => {
        opt.style.borderColor = '';
        opt.style.background  = '';
      });
      radio.closest('.radio-option').style.borderColor = 'var(--bleu-mer)';
      radio.closest('.radio-option').style.background  = 'rgba(26,107,138,0.08)';
    });
  });

  // ── Suivi visuel des checkboxes ───────────────────────────────────────────
  document.querySelectorAll('.check-option input[type="checkbox"]').forEach(cb => {
    cb.addEventListener('change', () => {
      // Si au moins une activité cochée → efface erreur
      if (cb.name === 'activites[]') {
        const any = document.querySelectorAll('input[name="activites[]"]:checked').length > 0;
        const wrap = document.getElementById('wrap-activites');
        if (any && wrap) {
          const errSpan = wrap.querySelector('.error-msg');
          if (errSpan) { errSpan.textContent = ''; errSpan.classList.remove('visible'); }
          wrap.classList.remove('field-error');
        }
      }
      // Règlement obligatoire
      if (cb.id === 'reglement') {
        const wrap = document.getElementById('reglement-wrap');
        if (cb.checked && wrap) {
          const errSpan = wrap.querySelector('.error-msg');
          if (errSpan) { errSpan.textContent = ''; errSpan.classList.remove('visible'); }
          wrap.classList.remove('field-error');
        }
      }
    });
  });

  // ── Soumission : validation complète avant envoi ──────────────────────────
  form.addEventListener('submit', (e) => {
    hideAlert();
    let hasError = false;

    // 1. Champs texte
    ['prenom', 'nom', 'email', 'telephone', 'naissance'].forEach(id => {
      const field = document.getElementById(id);
      if (!field || !RULES[id]) return;
      const msg = RULES[id](field.value.trim());
      setError(field, msg);
      if (msg) hasError = true;
    });

    // 2. Genre (boutons radio)
    const genreChecked = document.querySelector('input[name="genre"]:checked');
    const wrapGenre = document.getElementById('wrap-genre');
    if (!genreChecked && wrapGenre) {
      const errSpan = wrapGenre.querySelector('.error-msg');
      if (errSpan) { errSpan.textContent = 'Veuillez sélectionner votre genre.'; errSpan.classList.add('visible'); }
      wrapGenre.classList.add('field-error');
      hasError = true;
    }

    // 3. Niveau (boutons radio)
    const niveauChecked = document.querySelector('input[name="niveau"]:checked');
    const wrapNiveau = document.getElementById('wrap-niveau');
    if (!niveauChecked && wrapNiveau) {
      const errSpan = wrapNiveau.querySelector('.error-msg');
      if (errSpan) { errSpan.textContent = 'Veuillez sélectionner votre niveau.'; errSpan.classList.add('visible'); }
      wrapNiveau.classList.add('field-error');
      hasError = true;
    }

    // 4. Activités (au moins une checkbox)
    const activitesChecked = document.querySelectorAll('input[name="activites[]"]:checked').length;
    const wrapActivites = document.getElementById('wrap-activites');
    if (activitesChecked === 0 && wrapActivites) {
      const errSpan = wrapActivites.querySelector('.error-msg');
      if (errSpan) { errSpan.textContent = 'Veuillez sélectionner au moins une activité.'; errSpan.classList.add('visible'); }
      wrapActivites.classList.add('field-error');
      hasError = true;
    }

    // 5. Règlement obligatoire
    const reglement = document.getElementById('reglement');
    const wrapReg   = document.getElementById('reglement-wrap');
    if (reglement && !reglement.checked && wrapReg) {
      const errSpan = wrapReg.querySelector('.error-msg');
      if (errSpan) { errSpan.textContent = 'Vous devez accepter le règlement intérieur.'; errSpan.classList.add('visible'); }
      wrapReg.classList.add('field-error');
      hasError = true;
    }

    // Blocage si erreurs
    if (hasError) {
      e.preventDefault();
      showAlert('error', '❌ Veuillez corriger les erreurs signalées avant d\'envoyer le formulaire.');
      // Scroll vers la première erreur
      const firstErr = form.querySelector('.field-error');
      if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });

  // ── Réinitialisation propre ───────────────────────────────────────────────
  form.addEventListener('reset', () => {
    setTimeout(() => {
      form.querySelectorAll('.error-msg').forEach(el => {
        el.textContent = '';
        el.classList.remove('visible');
      });
      form.querySelectorAll('.field-error').forEach(el => el.classList.remove('field-error'));
      form.querySelectorAll('[aria-invalid]').forEach(el => el.removeAttribute('aria-invalid'));
      // Réinitialise les styles des options radio
      form.querySelectorAll('.radio-option').forEach(opt => {
        opt.style.borderColor = '';
        opt.style.background  = '';
      });
      hideAlert();
    }, 0);
  });
});
