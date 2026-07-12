(function () {
  function ready(callback) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', callback);
    } else {
      callback();
    }
  }

  ready(function () {
    var body = document.body;
    var openButton = document.querySelector('[data-sidebar-open]');
    var closeButton = document.querySelector('[data-sidebar-close]');
    var menuGroups = document.querySelectorAll('.admin-menu-group');
    var currentPath = window.location.pathname.split('/').pop() || 'index.php';
    var currentQuery = new URLSearchParams(window.location.search);

    body.classList.add('admin-page-' + currentPath.replace('.php', '').replace(/[^a-z0-9_-]/gi, ''));

    if (openButton) {
      openButton.addEventListener('click', function () {
        body.classList.add('admin-sidebar-open');
        openButton.setAttribute('aria-expanded', 'true');
      });
    }

    if (closeButton) {
      closeButton.addEventListener('click', function () {
        body.classList.remove('admin-sidebar-open');
        if (openButton) openButton.setAttribute('aria-expanded', 'false');
      });
    }

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && body.classList.contains('admin-sidebar-open')) {
        body.classList.remove('admin-sidebar-open');
        if (openButton) {
          openButton.setAttribute('aria-expanded', 'false');
          openButton.focus();
        }
      }
    });

    Array.prototype.forEach.call(menuGroups, function (group) {
      var toggle = group.querySelector('.admin-menu-toggle');
      var links = group.querySelectorAll('.admin-submenu a');

      if (group.classList.contains('active')) {
        group.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
      }

      Array.prototype.forEach.call(links, function (link) {
        var href = link.getAttribute('href');
        var hrefParts = href.split('?');
        var linkPath = hrefParts[0].replace('./', '');
        var isCurrent = linkPath === currentPath;

        if (isCurrent && currentPath === 'set.php' && hrefParts[1]) {
          isCurrent = new URLSearchParams(hrefParts[1]).get('mod') === currentQuery.get('mod');
        }

        if (isCurrent) {
          link.classList.add('current');
          group.classList.add('open');
          toggle.setAttribute('aria-expanded', 'true');
        }
      });

      toggle.addEventListener('click', function () {
        group.classList.toggle('open');
        toggle.setAttribute('aria-expanded', group.classList.contains('open') ? 'true' : 'false');
      });
    });

    var isDataPage = enhanceDataPages();
    enhanceSettingsPage(currentPath);
    enhanceLegacyPages(currentPath, isDataPage);
  });

  function enhanceDataPages() {
    var toolbar = document.getElementById('searchToolbar');
    var listTable = document.getElementById('listTable');
    var surface;

    if (!toolbar || !listTable) {
      return false;
    }

    toolbar.classList.add('admin-filterbar');
    surface = toolbar.closest('.center-block');
    if (surface) {
      surface.classList.add('admin-data-page');
    }

    addButtonIcon(toolbar.querySelector('button[type="submit"]'), 'fa-search');

    Array.prototype.forEach.call(toolbar.querySelectorAll('a, button'), function (control) {
      var text = control.textContent.trim();
      if (/高级搜索/.test(text)) addButtonIcon(control, 'fa-sliders');
      if (/添加商户|新增付款|新增/.test(text)) addButtonIcon(control, 'fa-plus');
      if (/导出/.test(text)) addButtonIcon(control, 'fa-download');
      if (/统计/.test(text)) addButtonIcon(control, 'fa-bar-chart');
    });

    return true;
  }

  function enhanceSettingsPage(currentPath) {
    var navigation;
    var page;

    if (currentPath !== 'set.php') {
      return;
    }

    page = document.querySelector('.admin-has-shell > .container .center-block');
    if (!page) {
      return;
    }

    page.classList.add('admin-settings-page');
    navigation = page.querySelector('.nav-pills');
    if (navigation) navigation.classList.add('admin-settings-nav');
  }

  function enhanceLegacyPages(currentPath, isDataPage) {
    var container;
    var surfaces;

    if (isDataPage || currentPath === 'index.php' || currentPath === 'set.php') {
      return;
    }

    container = document.querySelector('.admin-has-shell > .container, .admin-has-shell > .container-fluid');
    if (!container) {
      return;
    }

    container.classList.add('admin-legacy-container');
    surfaces = container.querySelectorAll('.center-block');
    Array.prototype.forEach.call(surfaces, function (surface) {
      surface.classList.add('admin-form-page');
    });

    Array.prototype.forEach.call(container.querySelectorAll('.nav-tabs'), function (tabs) {
      tabs.classList.add('admin-section-tabs');
    });

    Array.prototype.forEach.call(container.querySelectorAll('.panel'), function (panel) {
      panel.classList.add('admin-legacy-panel');
    });

    Array.prototype.forEach.call(container.querySelectorAll('table'), function (table) {
      var wrapper;
      if (table.closest('.table-responsive, .fixed-table-container, .admin-legacy-table-wrap')) {
        return;
      }
      wrapper = document.createElement('div');
      wrapper.className = 'admin-legacy-table-wrap';
      table.parentNode.insertBefore(wrapper, table);
      wrapper.appendChild(table);
    });

    Array.prototype.forEach.call(container.querySelectorAll('a.btn, button.btn, span.btn'), function (control) {
      var text = control.textContent.trim();
      if (/新增|添加|追加|生成/.test(text)) addButtonIcon(control, 'fa-plus');
      if (/导出|下载/.test(text)) addButtonIcon(control, 'fa-download');
      if (/保存|确认|提交/.test(text)) addButtonIcon(control, 'fa-check');
      if (/删除|清理/.test(text)) addButtonIcon(control, 'fa-trash-o');
      if (/编辑|修改|配置/.test(text)) addButtonIcon(control, 'fa-pencil');
      if (/返回/.test(text)) addButtonIcon(control, 'fa-arrow-left');
    });
  }

  function addButtonIcon(control, iconClass) {
    var icon;

    if (!control || control.querySelector('i')) {
      return;
    }

    icon = document.createElement('i');
    icon.className = 'fa ' + iconClass;
    control.insertBefore(icon, control.firstChild);
  }
})();
