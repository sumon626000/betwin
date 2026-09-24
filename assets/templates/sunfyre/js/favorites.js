/**
 * BET369WIN game favorites (heart on each tile).
 * Stored in localStorage so it works logged-out and logged-in.
 */
(function (window, document) {
  'use strict';

  var KEY = 'b369_favorites_v1';
  var LOGIN = window.RV_LOGIN_URL || '/user/login';
  var LAUNCH = window.RV_LAUNCH_BASE || '/user/jili/launch';
  var LOGGED = !!window.RV_LOGGED_IN;

  function load() {
    try {
      var raw = localStorage.getItem(KEY);
      var arr = raw ? JSON.parse(raw) : [];
      return Array.isArray(arr) ? arr : [];
    } catch (e) {
      return [];
    }
  }

  function save(list) {
    try {
      localStorage.setItem(KEY, JSON.stringify(list));
    } catch (e) {}
    document.dispatchEvent(new CustomEvent('b369:fav-changed', { detail: { count: list.length } }));
  }

  function favKey(id, provider) {
    return String(id || '') + '|' + String(provider || 'jili').toLowerCase();
  }

  function list() {
    return load();
  }

  function findIndex(list, id, provider) {
    var k = favKey(id, provider);
    for (var i = 0; i < list.length; i++) {
      if (favKey(list[i].id, list[i].provider) === k) return i;
    }
    return -1;
  }

  function has(id, provider) {
    return findIndex(load(), id, provider) >= 0;
  }

  function parseCard(card) {
    if (!card) return null;
    var link = card.matches('a[href]') ? card : card.querySelector('a[href]');
    var href = link ? link.getAttribute('href') || '' : '';
    var id = card.getAttribute('data-game-id') || '';
    var provider = card.getAttribute('data-provider') || 'jili';
    var name = card.getAttribute('data-game-name') || '';
    var img = card.getAttribute('data-game-img') || '';

    try {
      var u = new URL(href, window.location.origin);
      if (!id) id = u.searchParams.get('game_code') || '';
      if (u.searchParams.get('provider')) provider = u.searchParams.get('provider');
    } catch (e) {}

    if (!name) {
      var titleEl = card.querySelector('.game-card-name, [title]');
      name = (titleEl && (titleEl.textContent || titleEl.getAttribute('title'))) || '';
      name = String(name).trim();
    }
    if (!img) {
      var im = card.querySelector('img');
      img = im ? im.getAttribute('src') || '' : '';
    }
    if (!id) return null;
    return { id: id, provider: provider || 'jili', name: name || 'Game', img: img || '' };
  }

  function toggle(game) {
    if (!game || !game.id) return false;
    var list = load();
    var i = findIndex(list, game.id, game.provider);
    var on;
    if (i >= 0) {
      list.splice(i, 1);
      on = false;
    } else {
      list.unshift({
        id: String(game.id),
        provider: String(game.provider || 'jili').toLowerCase(),
        name: game.name || 'Game',
        img: game.img || '',
        at: Date.now()
      });
      if (list.length > 200) list = list.slice(0, 200);
      on = true;
    }
    save(list);
    return on;
  }

  function setHeartState(btn, on) {
    if (!btn) return;
    btn.classList.toggle('active', !!on);
    btn.setAttribute('aria-pressed', on ? 'true' : 'false');
    var icon = btn.querySelector('i');
    if (icon) {
      icon.className = on ? 'fas fa-heart' : 'far fa-heart';
    }
  }

  function ensureHeart(card) {
    if (!card || card.querySelector('.game-card-fav') || (card.parentElement && card.parentElement.querySelector(':scope > .game-card-fav'))) return;
    var game = parseCard(card);
    if (!game) return;

    card.setAttribute('data-game-id', game.id);
    card.setAttribute('data-provider', game.provider);
    card.setAttribute('data-game-name', game.name);
    if (game.img) card.setAttribute('data-game-img', game.img);

    var host = card;
    if (card.tagName === 'A') {
      var wrap = card.parentElement;
      if (!wrap || !wrap.classList.contains('game-card-wrap')) {
        wrap = document.createElement('div');
        wrap.className = 'game-card-wrap';
        card.parentNode.insertBefore(wrap, card);
        wrap.appendChild(card);
      }
      host = wrap;
    } else {
      var style = window.getComputedStyle(card);
      if (style.position === 'static') card.style.position = 'relative';
    }

    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'game-card-fav';
    btn.setAttribute('aria-label', 'Favorite');
    btn.innerHTML = '<i class="far fa-heart"></i>';
    setHeartState(btn, has(game.id, game.provider));

    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      var g = parseCard(card);
      if (!g) return;
      var on = toggle(g);
      setHeartState(btn, on);
    });

    host.appendChild(btn);
  }

  function decorate(root) {
    var scope = root || document;
    var cards = scope.querySelectorAll('.game-card, .game-item-box.game-card');
    for (var i = 0; i < cards.length; i++) {
      ensureHeart(cards[i]);
    }
  }

  function syncHearts() {
    var buttons = document.querySelectorAll('.game-card-fav');
    for (var i = 0; i < buttons.length; i++) {
      var card = buttons[i].closest('.game-card, .game-item-box');
      var g = parseCard(card);
      if (g) setHeartState(buttons[i], has(g.id, g.provider));
    }
  }

  function esc(s) {
    return String(s || '').replace(/[&<>"']/g, function (c) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c];
    });
  }

  function renderFavoritesGrid(gridEl, emptyEl) {
    var favs = load();
    if (!gridEl) return;
    gridEl.innerHTML = '';
    if (!favs.length) {
      gridEl.style.display = 'none';
      if (emptyEl) emptyEl.style.display = 'block';
      return;
    }
    if (emptyEl) emptyEl.style.display = 'none';
    gridEl.style.display = '';
    var frag = document.createDocumentFragment();
    favs.forEach(function (game) {
      var href = LOGGED
        ? LAUNCH + '?game_code=' + encodeURIComponent(game.id) + '&provider=' + encodeURIComponent(game.provider || 'jili')
        : LOGIN;
      var el = document.createElement('div');
      el.className = 'game-card';
      el.dataset.status = '1';
      el.setAttribute('data-game-id', game.id);
      el.setAttribute('data-provider', game.provider || 'jili');
      el.setAttribute('data-game-name', game.name || 'Game');
      if (game.img) el.setAttribute('data-game-img', game.img);
      var imgHtml = game.img
        ? '<img class="game-card-img" src="' + esc(game.img) + '" alt="' + esc(game.name) + '" loading="lazy" referrerpolicy="no-referrer">'
        : '<div class="game-card-img" style="display:flex;align-items:center;justify-content:center;background:#123b66;color:#fff;font-weight:800;font-size:18px;">' +
          esc((game.name || 'G').charAt(0).toUpperCase()) +
          '</div>';
      el.innerHTML =
        '<a href="' +
        esc(href) +
        '" class="game-card-img" title="' +
        esc(game.name) +
        '" style="display:block;position:relative;">' +
        imgHtml +
        '</a><div class="game-card-name">' +
        esc(game.name || 'Game') +
        '</div>';
      frag.appendChild(el);
    });
    gridEl.appendChild(frag);
    decorate(gridEl);
  }

  function showFavorites() {
    if (typeof window.filterGames === 'function') {
      var pill = document.querySelector('.cat-pill[data-cat="favorite"]');
      window.filterGames('favorite', pill || null);
      return;
    }
    var section = document.getElementById('favorites-section');
    if (section) {
      document.querySelectorAll('.section-container').forEach(function (s) {
        s.style.display = 'none';
      });
      var pg = document.getElementById('provider-grid-container');
      if (pg) pg.style.display = 'none';
      section.style.display = 'block';
      renderFavoritesGrid(
        document.getElementById('favorites-grid'),
        document.getElementById('favorites-empty')
      );
    }
  }

  window.B369Fav = {
    list: list,
    has: has,
    toggle: toggle,
    decorate: decorate,
    syncHearts: syncHearts,
    renderFavoritesGrid: renderFavoritesGrid,
    showFavorites: showFavorites,
    count: function () {
      return load().length;
    }
  };

  document.addEventListener('DOMContentLoaded', function () {
    decorate(document);
    var obs = new MutationObserver(function (mutations) {
      for (var i = 0; i < mutations.length; i++) {
        var nodes = mutations[i].addedNodes;
        for (var j = 0; j < nodes.length; j++) {
          var n = nodes[j];
          if (n.nodeType !== 1) continue;
          if (n.matches && (n.matches('.game-card') || n.matches('.game-item-box'))) {
            ensureHeart(n);
          }
          if (n.querySelectorAll) decorate(n);
        }
      }
    });
    obs.observe(document.body, { childList: true, subtree: true });
  });

  document.addEventListener('b369:fav-changed', function () {
    syncHearts();
  });
})(window, document);
