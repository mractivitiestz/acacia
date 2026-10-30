# Class mapping: the engine's classes → the framework's hooks

The complete map from every class of the design engine's `app.css` and `expeditions.css` to its C7 hook in this package, for slice 0b (updating `app.js`, `expedition.js` and `pages.py`). The same data is in `mapping.json`, machine-readable.

## Contents

- [How to apply it](#how-to-apply-it)
- [Classes](#classes)
- [States and variants](#states-and-variants)
- [Parts that were element selectors](#parts-that-were-element-selectors)
- [Elements that keep a tag](#elements-that-keep-a-tag)
- [Ids](#ids)
- [Dropped](#dropped)
- [JavaScript changes for slice 0b](#javascript-changes-for-slice-0b)

## How to apply it

In this order, on the markup the engine writes:

1. **Parts**: every element matched by `<in> <el>` (old classes) gets the part class (the framework never selects `h3`, `b`, `span`, `a`, … inside a component).
2. **Tags**: an element written with a tag-qualified rule (`ol.how`) gets `data-as="<tag>"`.
3. **Classes**: each old class is replaced by its new class; a value in brackets is an attribute instead (`[data-map="none"]`), a value `a[b]` is both.
4. **States and variants**: an old modifier class on a block (the block is the element's first renamed class or part) becomes the attribute in the table; the old class goes.
5. The day rows: `.ta-rc.on` is drawn from its toggle's `aria-expanded="true"` (`.day:has([aria-expanded="true"])`), so the toggle carries the state and the row needs no class.

## Classes

| Old | New |
|---|---|
| `.ab` | `.about-item` |
| `.ab3` | `.about` |
| `.acc` | `.accordion` |
| `.acc-b` | `.accordion-body` |
| `.achips` | `.chips` |
| `.aside-x` | `.aside-extra` |
| `.biz` | `.business` |
| `.biz-l` | `.business-lead` |
| `.bk-al` | `.booking-alert` |
| `.bk-am` | `.booking-amount` |
| `.bk-ask` | `.ask` |
| `.bk-asum` | `.booking-asum` |
| `.bk-back` | `.checkout-back` |
| `.bk-bb` | `.booking-bb` |
| `.bk-blk` | `.checkout-block` |
| `.bk-cal` | `.calendar` |
| `.bk-cal-g` | `.calendar-grid` |
| `.bk-cal-h` | `.calendar-head` |
| `.bk-cal-t` | `.calendar-title` |
| `.bk-chip` | `.status` |
| `.bk-chk` | `.check` |
| `.bk-co` | `.checkout` |
| `.bk-co-a` | `.checkout-aside` |
| `.bk-co-dl` | `.checkout-dl` |
| `.bk-co-g` | `.checkout-grid` |
| `.bk-co-m` | `.checkout-main` |
| `.bk-co-s` | `.checkout-summary` |
| `.bk-co-sb` | `.checkout-sumbody` |
| `.bk-d` | `.calendar-day` |
| `.bk-df` | `.datefield-btn` |
| `.bk-dn` | `.booking-down` |
| `.bk-dow` | `.calendar-dow` |
| `.bk-dw` | `.datefield` |
| `.bk-e` | `.error` |
| `.bk-facts` | `.booking-facts` |
| `.bk-fine` | `.ask-fine` |
| `.bk-fs` | `.fieldset` |
| `.bk-gp` | `.wallet-gp` |
| `.bk-gp-w` | `.wallet-gpw` |
| `.bk-hint` | `.booking-hint` |
| `.bk-ib` | `.calendar-nav` |
| `.bk-key` | `.calendar-key` |
| `.bk-lab` | `.booking-label` |
| `.bk-lab2` | `.booking-label2` |
| `.bk-len` | `.booking-length` |
| `.bk-li` | `.booking-line` |
| `.bk-me` | `.booking-me` |
| `.bk-mm` | `.option-mark` |
| `.bk-o` | `.booking-or` |
| `.bk-okc` | `.checkout-ok` |
| `.bk-on` | `[data-booking="on"]` |
| `.bk-one` | `.booking-one` |
| `.bk-opt` | `.option` |
| `.bk-opts` | `.options` |
| `.bk-or` | `.pay-or` |
| `.bk-otp` | `.otp` |
| `.bk-otp-a` | `.otp-again` |
| `.bk-otp-b` | `.otp-boxes` |
| `.bk-otp-i` | `.otp-input` |
| `.bk-otp-s` | `.otp-status` |
| `.bk-pass` | `.pass` |
| `.bk-pass-ft` | `.pass-foot` |
| `.bk-pass-m` | `.pass-mail` |
| `.bk-pay` | `.pay` |
| `.bk-pm` | `.pay-methods` |
| `.bk-pop` | `.datefield-pop` |
| `.bk-por` | `[data-booking-portrait]` |
| `.bk-pp` | `.booking-people` |
| `.bk-rm` | `.booking-remove` |
| `.bk-sb` | `.booking-step` |
| `.bk-sec` | `.pay-secure` |
| `.bk-sent` | `.booking-sent` |
| `.bk-sl` | `.booking-sl` |
| `.bk-st` | `.booking-stepper` |
| `.bk-start` | `.booking-start` |
| `.bk-sum` | `.booking-sum` |
| `.bk-trip` | `.trip` |
| `.bk-trip-r` | `.trip-row` |
| `.bk-tt` | `.booking-total` |
| `.bk-vc` | `.checkout-confirm` |
| `.bk-vc-a` | `.checkout-actions` |
| `.bk-vc-top` | `.checkout-top` |
| `.bk-w` | `.booking` |
| `.bk-wal` | `.wallet` |
| `.bk-why` | `.booking-why` |
| `.bk-xp` | `.pay-express` |
| `.btn` | `.btn` |
| `.btns` | `.btns` |
| `.cap` | `.caption` |
| `.car` | `.carousel` |
| `.car-ctl` | `.carousel-controls` |
| `.card` | `.card` |
| `.cat-links` | `.typelinks` |
| `.cats` | `.kinds` |
| `.cb` | `.carousel-btn` |
| `.contact` | `.contact` |
| `.count` | `.resultsbar-count` |
| `.cr-back` | `.credits-back` |
| `.cr-cl` | `.credits-list` |
| `.cr-cm` | `.credits-cm` |
| `.cr-cp` | `.credits` |
| `.cr-cs` | `.credits-section` |
| `.cr-lead` | `.credits-lead` |
| `.cr-n` | `.credits-num` |
| `.cr-p` | `.credits-p` |
| `.cr-si` | `.credits-sites` |
| `.cr-src` | `.credits-src` |
| `.crumb` | `.crumbs` |
| `.crumb-up` | `.crumbs-up` |
| `.cs-all` | `.types-all` |
| `.cs-in` | `.types-inner` |
| `.cs-sc` | `.types-scroll` |
| `.cstrip` | `.types` |
| `.ct` | `.kind` |
| `.ct-i` | `.kind-media` |
| `.ct-ic` | `.kind-icon` |
| `.ct-ph` | `.kind-placeholder` |
| `.dist` | `.distance` |
| `.dot` | `.dot` |
| `.dsel` | `.select` |
| `.dt` | `.headline` |
| `.dt-a` | `.headline-actions` |
| `.dt-bar` | `.bar` |
| `.dt-bar-t` | `.bar-text` |
| `.dt-h` | `.headline-head` |
| `.dt-t` | `.headline-title` |
| `.dt-tabs` | `.tabs` |
| `.emp` | `.nothing` |
| `.emp-i` | `.nothing-icon` |
| `.empty` | `.empty` |
| `.en` | `.en` |
| `.enq` | `.enquiry` |
| `.enq-b` | `.header-enquire` |
| `.ext` | `.plan-ext` |
| `.eyebrow` | `.eyebrow` |
| `.facts` | `.facts` |
| `.fg` | `.facet` |
| `.fg-b` | `.facet-body` |
| `.fg-h` | `.facet-heading` |
| `.fg-in` | `.facet-inner` |
| `.fg-ma` | `.facet-lesslabel` |
| `.fg-mf` | `.facet-morelabel` |
| `.fg-more` | `.facet-more` |
| `.fg-o` | `.facet-options` |
| `.fg-s` | `.facet-select` |
| `.fg-t` | `.facet-toggle` |
| `.fg-v` | `.facet-value` |
| `.fld` | `.field` |
| `.fo` | `.choice` |
| `.form` | `.form` |
| `.fq` | `.filters-query` |
| `.fs` | `.filters` |
| `.fs-b` | `.filters-btn` |
| `.fs-bd` | `.filters-body` |
| `.fs-ft` | `.filters-foot` |
| `.fs-hd` | `.filters-head` |
| `.fs-open` | `[data-filters="open"]` |
| `.ft` | `.footer` |
| `.ft-al` | `.footer-about` |
| `.ft-badges` | `.footer-badges` |
| `.ft-band` | `.footer-band` |
| `.ft-bl` | `.footer-legal` |
| `.ft-bot` | `.footer-bottom` |
| `.ft-chips` | `.footer-chips` |
| `.ft-col` | `.footer-col` |
| `.ft-cr` | `.footer-copy` |
| `.ft-h` | `.footer-heading` |
| `.ft-leg` | `.footer-legalrow` |
| `.ft-lm` | `.footer-lang` |
| `.ft-ls` | `.footer-links` |
| `.ft-mega` | `.footer-mega` |
| `.ft-mt` | `.footer-main` |
| `.ft-pk` | `.footer-pack` |
| `.ft-s` | `.footer-small` |
| `.ft-sb` | `.footer-storebadge` |
| `.ft-seal` | `.logo-footer` |
| `.ft-st` | `.footer-strap` |
| `.ft-tc` | `.footer-contact` |
| `.ft-types` | `.footer-types` |
| `.ft-word` | `.footer-word` |
| `.ft-z` | `.footer-zone` |
| `.ft-zg` | `.footer-zones` |
| `.ft-zh` | `.footer-zonehead` |
| `.ft-zn` | `.footer-zonename` |
| `.full` | `.form-full` |
| `.gal` | `.gallery` |
| `.gal-all` | `.gallery-all` |
| `.gal-c` | `.gallery-count` |
| `.gal-g` | `.gallery-grid` |
| `.gal-i` | `.gallery-item` |
| `.gal-l` | `.gallery-label` |
| `.gal-s` | `.gallery-strip` |
| `.gal-si` | `.gallery-slide` |
| `.gm-c` | `.map-canvas` |
| `.gm-cd` | `.mapcard` |
| `.gm-cd-b` | `.mapcard-body` |
| `.gm-cd-f` | `.mapcard-foot` |
| `.gm-cd-i` | `.mapcard-media` |
| `.gm-i` | `.map-item` |
| `.gm-k` | `.map-key` |
| `.gm-kk` | `.map-keyitem` |
| `.gm-l` | `.map-list` |
| `.gm-m` | `.marker` |
| `.gm-note` | `.map-note` |
| `.gm-pop` | `.popup` |
| `.gmap` | `.map` |
| `.grid` | `.grid` |
| `.has-bar` | `[data-bar]` |
| `.hcap` | `.search-small[data-variant="caption"]` |
| `.hcap-p` | `.search-caption` |
| `.hd` | `.header` |
| `.hd-in` | `.header-inner` |
| `.hd-r` | `.header-actions` |
| `.hd-s` | `.header-search` |
| `.hd-up` | `[data-header="up"]` |
| `.hd-wrap` | `.header-wrap` |
| `.heart` | `.heart` |
| `.hero` | `.hero` |
| `.hero-g` | `.hero-grid` |
| `.hero-l` | `.hero-lead` |
| `.hero-map` | `.heromap` |
| `.hero-t` | `.hero-text` |
| `.hl` | `.checks` |
| `.hmo` | `.heromosaic` |
| `.hmo-l` | `.heromosaic-label` |
| `.hmo-t` | `.heromosaic-tile` |
| `.how` | `.steps` |
| `.how-i` | `.steps-icon` |
| `.hv` | `.view` |
| `.hv-fig` | `.view-figure` |
| `.hv-key` | `.view-key` |
| `.hv-ln` | `.view-line` |
| `.hv-mv` | `.view-map` |
| `.hv-pv` | `.view-photos` |
| `.hv-sb` | `.segmented-btn` |
| `.hv-seg` | `.segmented` |
| `.hv-sw` | `.view-switch` |
| `.hv-v` | `.view-pane` |
| `.ib` | `.iconbtn` |
| `.ic` | `.icon` |
| `.ind` | `.price-ind` |
| `.k-d` | `.calendar-dep` |
| `.k-good` | `.months-good` |
| `.k-now` | `.months-now` |
| `.lab` | `.tag` |
| `.labs` | `.tags` |
| `.lb` | `.lightbox` |
| `.lb-a` | `.lightbox-arrow` |
| `.lb-f` | `.lightbox-figure` |
| `.lb-l` | `.lightbox-label` |
| `.lb-n` | `.lightbox-count` |
| `.lb-nx` | `.lightbox-next` |
| `.lb-p` | `.lightbox-prev` |
| `.lb-st` | `.lightbox-stage` |
| `.lb-th` | `.lightbox-thumbs` |
| `.lb-top` | `.lightbox-top` |
| `.lbody` | `.detail` |
| `.lc4` | `.activity` |
| `.lc4-a` | `.activity-link` |
| `.lc4-b` | `.activity-body` |
| `.lc4-c` | `.activity-type` |
| `.lc4-i` | `.activity-media` |
| `.lc4-m` | `.activity-meta` |
| `.lc4-v` | `.activity-view` |
| `.ldl` | `.deflist` |
| `.lead` | `.lead` |
| `.lgo` | `.logo` |
| `.lgo-disc` | `.logo-disc` |
| `.lgo-p` | `.logo-pill` |
| `.lgo-s` | `.logo-seal` |
| `.lgo-st` | `.logo-stitch` |
| `.lh-m` | `.headline-meta` |
| `.lm` | `.lang` |
| `.lm-c` | `.lang-code` |
| `.lm-n` | `.lang-name` |
| `.lm-v` | `.lang-value` |
| `.lmain` | `.detail-main` |
| `.lnk` | `.link` |
| `.lnote` | `.lnote` |
| `.loc` | `.location` |
| `.loc-ph` | `.location-placeholder` |
| `.locked` | `[data-locked]` |
| `.lp` | `.prose` |
| `.lsec` | `.detail-sec` |
| `.lside` | `.aside` |
| `.mark` | `.mark` |
| `.mc-ph` | `.placeholder-thumb` |
| `.menu-b` | `.header-menubtn` |
| `.mk` | `.pin` |
| `.mk-d` | `.pin-dot` |
| `.mk-kk` | `.pin-key` |
| `.mk-kt` | `.pin-keytext` |
| `.mk-lb` | `.pin-label` |
| `.mk-li` | `.pin-list` |
| `.mk-sw` | `.pin-swatch` |
| `.mk-ty` | `.pin-type` |
| `.mm` | `.mega` |
| `.mm-all` | `.mega-all` |
| `.mm-g` | `.mega-group` |
| `.mm-h` | `.mega-heading` |
| `.mm-in` | `.mega-inner` |
| `.mm-l` | `.mega-link` |
| `.mm-ls` | `.mega-links` |
| `.mm-p` | `.mega-place` |
| `.mm-ph` | `.mega-places` |
| `.mm-pi` | `.mega-photo` |
| `.mm-ps` | `.mega-grid` |
| `.mob` | `.drawer` |
| `.mob-b` | `.drawer-body` |
| `.mob-enq` | `.drawer-enquire` |
| `.mob-h` | `.drawer-head` |
| `.mob-r` | `.drawer-row` |
| `.mon` | `[data-section="months"]` |
| `.mosaic` | `.pagehead-media[data-variant="mosaic"]` |
| `.muted` | `.muted` |
| `.nav` | `.nav` |
| `.nav-a` | `.nav-link` |
| `.nomap` | `[data-map="none"]` |
| `.none` | `.none` |
| `.note` | `.note` |
| `.nw` | `.nw` |
| `.ob` | `.badge` |
| `.ob-row` | `.offbeat` |
| `.pair-h` | `.pair-head` |
| `.pairc` | `.pair` |
| `.pc` | `.region` |
| `.pc-go` | `.region-go` |
| `.pc-t` | `.region-text` |
| `.ph` | `.pagehead` |
| `.ph-g` | `.pagehead-grid` |
| `.ph-i` | `.pagehead-media` |
| `.ph-t` | `.pagehead-text` |
| `.ph0` | `.placeholder` |
| `.pill` | `.pill` |
| `.pills` | `.pills` |
| `.pl-l` | `.plan-layout` |
| `.plr` | `.place` |
| `.plr-b` | `.place-body` |
| `.plr-d` | `.place-desc` |
| `.plr-e` | `.place-empty` |
| `.plr-fb` | `.place-fallback` |
| `.plr-g` | `.place-region` |
| `.plr-h` | `.place-title` |
| `.plr-l` | `.place-list` |
| `.plr-m` | `.place-meta` |
| `.plr-map` | `.place-map` |
| `.plr-mt` | `.place-maptag` |
| `.plr-ob` | `.place-badge` |
| `.plr-pa` | `.place-photolink` |
| `.plr-ph` | `.place-photo` |
| `.plr-pt` | `.place-previewtext` |
| `.plr-pv` | `.place-preview` |
| `.plr-th` | `.place-thumb` |
| `.plr-x` | `.place-more` |
| `.pls` | `.places` |
| `.pr` | `.price` |
| `.pr-k` | `.price-label` |
| `.prev` | `[data-variant="preview"]` |
| `.promise` | `.promise` |
| `.promise-i` | `.promise-icon` |
| `.promise-t` | `.promise-text` |
| `.psec` | `.plan-sec` |
| `.qchip` | `.chip` |
| `.qclear` | `.chips-clear` |
| `.quick` | `.quick` |
| `.quick-k` | `.quick-label` |
| `.quick-l` | `.quick-list` |
| `.rc` | `.result` |
| `.rc-a` | `.result-arrow` |
| `.rc-b` | `.result-body` |
| `.rc-d` | `.result-dots` |
| `.rc-f` | `.result-foot` |
| `.rc-ph` | `.result-photo` |
| `.rc-s` | `.result-slides` |
| `.rc-t` | `.result-text` |
| `.rg-f` | `.segmented[data-variant="region"]` |
| `.rows` | `.rows` |
| `.rs` | `.results` |
| `.rs-m` | `.results-main` |
| `.rs-pills` | `.resultsbar-pills` |
| `.rs-top` | `.resultsbar` |
| `.rs-tr` | `.resultsbar-tools` |
| `.sample` | `.sample` |
| `.save` | `.save` |
| `.sbox` | `.search` |
| `.sbox-s` | `.search-small` |
| `.sc-p` | `.ask-p` |
| `.sc-who` | `.ask-who` |
| `.scope` | `.scopes-tab` |
| `.scopes` | `.scopes` |
| `.scrolled` | `[data-scrolled]` |
| `.sea` | `.season` |
| `.seas` | `.seasons` |
| `.sec` | `.sec` |
| `.see` | `.nw` |
| `.sg` | `.suggest-item` |
| `.sg-h` | `.suggest-heading` |
| `.sg-ic` | `.suggest-icon` |
| `.sg-none` | `.suggest-none` |
| `.sgo` | `.search-go` |
| `.sh` | `.sechead` |
| `.sh-r` | `.sechead-actions` |
| `.share-msg` | `.share` |
| `.skip` | `.skip` |
| `.sl` | `.srclink` |
| `.sm` | `.search-month` |
| `.smd` | `.twodays` |
| `.smd-day` | `.twodays-day` |
| `.smd-days` | `.twodays-days` |
| `.smd-db` | `.twodays-box` |
| `.smd-dn` | `.twodays-num` |
| `.smd-m` | `.twodays-meta` |
| `.smd-pick` | `.twodays-pick` |
| `.smd-plus` | `.twodays-plus` |
| `.smsg` | `.search-msg` |
| `.soon` | `.soon` |
| `.sortc` | `.resultsbar-sort` |
| `.sp-b` | `.header-searchbtn` |
| `.sp-h` | `.sheet-head` |
| `.sp-p` | `.sheet` |
| `.sq` | `.search-query` |
| `.srcs` | `.sources` |
| `.st` | `.month` |
| `.st-c` | `.month-bar` |
| `.st-d` | `.month-dot` |
| `.stay` | `.stay` |
| `.stay-i` | `.stay-icon` |
| `.stays` | `.stays` |
| `.strip` | `.months` |
| `.strip-c` | `.months-caption` |
| `.strip-k` | `.months-key` |
| `.sub` | `.subsec` |
| `.sub-h` | `.subsec-heading` |
| `.sugg` | `.suggest` |
| `.sv` | `.saved` |
| `.sv-p` | `.saved-panel` |
| `.svc4-i` | `.plan-icon` |
| `.sw` | `.sw` |
| `.ta` | `.itinerary` |
| `.ta-acc` | `.days-acc` |
| `.ta-accs` | `.days-accs` |
| `.ta-b` | `.days-blurb` |
| `.ta-df` | `.days-df` |
| `.ta-dn` | `.day-disc` |
| `.ta-dot` | `.days-dot` |
| `.ta-dt` | `.days-dt` |
| `.ta-fl` | `.day-travel` |
| `.ta-grp` | `.days-grp` |
| `.ta-hd` | `.day-head` |
| `.ta-hl` | `.days-hl` |
| `.ta-k` | `.days-k` |
| `.ta-left` | `.days-left` |
| `.ta-more` | `.days-more` |
| `.ta-n` | `.days-n` |
| `.ta-nt` | `.day-night` |
| `.ta-ph` | `.days-photo` |
| `.ta-r` | `.days-row` |
| `.ta-rail` | `.days-rail` |
| `.ta-rc` | `.day` |
| `.ta-rcb` | `.day-body` |
| `.ta-rows` | `.days` |
| `.ta-rt` | `.day-toggle` |
| `.ta-sep` | `.day-sep` |
| `.ta-st` | `.days-st` |
| `.ta-sum` | `.days-summary` |
| `.ta-t` | `.day-text` |
| `.ta-tl` | `.days-tl` |
| `.ta-tli` | `.days-tli` |
| `.ta-tr` | `.day-modes` |
| `.ta-v` | `.days-view` |
| `.ta-vd` | `.days-vd` |
| `.ta-vm` | `.days-vm` |
| `.toc` | `.toc` |
| `.v5` | `.pass-card` |
| `.v5-f` | `.pass-fields` |
| `.v5-foot` | `.pass-foot2` |
| `.v5-q` | `.pass-qr` |
| `.v5-ref` | `.pass-ref` |
| `.v5-top` | `.pass-top` |
| `.v5-what` | `.pass-what` |
| `.vc` | `.voucher` |
| `.vc-act` | `.voucher-act` |
| `.vc-help` | `.voucher-help` |
| `.vc-lab` | `.voucher-label` |
| `.vc-mark` | `.voucher-mark` |
| `.vc-mk` | `.voucher-mk` |
| `.vc-mk-d` | `.voucher-mkdisc` |
| `.vc-mk-s` | `.voucher-mkseal` |
| `.vc-qr` | `.voucher-qr` |
| `.vc-qr-b` | `.voucher-qrbox` |
| `.vc-qr-m` | `.voucher-qrmark` |
| `.vc-show` | `.voucher-show` |
| `.vcw` | `.voucher-wrap` |
| `.vh` | `.vh` |
| `.when` | `[data-section="when"]` |
| `.word` | `.wordmark` |
| `.wrap` | `.wrap` |
| `.xb-pl` | `.capacity-places` |
| `.xcap` | `.capacity` |
| `.xe-card` | `.expeditions-card` |
| `.xe-g` | `.expeditions-g` |
| `.xe-grid` | `.expeditions-grid` |
| `.xe-no` | `.expeditions-none` |
| `.xe-soon` | `.expeditions-soon` |
| `.xe2` | `.expedition` |
| `.xe2-b` | `.expedition-body` |
| `.xe2-cap` | `.expedition-cap` |
| `.xe2-f` | `.expedition-foot` |
| `.xe2-h` | `.expedition-head` |
| `.xe2-m` | `.expedition-meta` |
| `.xe2-ph` | `.expedition-photo` |
| `.xe2-r` | `.expedition-row` |
| `.xg-chip` | `.grade` |
| `.xg-gal` | `.gallery[data-variant="expedition"]` |
| `.xg-lb` | `.lightbox[data-variant="expedition"]` |
| `.xnav` | `.header-pill` |
| `.xres` | `.resultsbar-more` |

## States and variants

Old modifier class on a block → the attribute (or class) that replaces it. ARIA where the component needs the state for accessibility anyway; `data-*` for variants (named after the Twig prop) and for the few visual states ARIA has no word for (`data-state`).

| Block | Old class | New hook |
|---|---|---|
| `.accordion` | `.cur` | `[aria-current="true"]` |
| `.accordion` | `.days-grp` | `.days-grp` |
| `.badge` | `.on-ph` | `[data-variant="photo"]` |
| `.booking-why` | `.sm` | `[data-size="sm"]` |
| `.btn` | `.bk-off` | `[data-variant="off"]` |
| `.btn` | `.brand` | `[data-variant="brand"]` |
| `.btn` | `.ghost` | `[data-variant="ghost"]` |
| `.btn` | `.wide` | `[data-width="full"]` |
| `.btns` | `.c` | `[data-align="center"]` |
| `.calendar` | `.bad` | `[data-state="invalid"]` |
| `.calendar-day` | `.dep` | `[data-state="departure"]` |
| `.calendar-day` | `.full` | `[data-state="full"]` |
| `.calendar-day` | `.on` | `[aria-pressed="true"]` |
| `.calendar-day` | `.rng` | `[data-state="range"]` |
| `.capacity-seat` | `.on` | `[data-state="on"]` |
| `.carousel` | `.cards` | `[data-variant="cards"]` |
| `.carousel` | `.places` | `[data-variant="places"]` |
| `.choice` | `.more` | `[data-variant="more"]` |
| `.choice` | `.on` | `[aria-pressed="true"]` |
| `.choice` | `.zero` | `[data-count="0"]` |
| `.datefield-btn` | `.bad` | `[aria-invalid="true"]` |
| `.datefield-btn` | `.set` | `[data-state="set"]` |
| `.day` | `.nopic` | `[data-variant="nophoto"]` |
| `.day` | `.on` | `:has([aria-expanded="true"])` |
| `.days` | `.rows` | `.rows` |
| `.drawer-row` | `.cur` | `[aria-current="true"]` |
| `.expedition` | `.on` | `[data-state="on"]` |
| `.expeditions-card` | `.on` | `[data-state="on"]` |
| `.expeditions-grid` | `.four` | `[data-variant="four"]` |
| `.facet` | `.all` | `[data-state="all"]` |
| `.facet` | `.shut` | `[data-state="shut"]` |
| `.field` | `.full` | `.form-full` |
| `.gallery` | `.nopic` | `[data-variant="nophoto"]` |
| `.gallery` | `.xg-gal` | `[data-variant="expedition"]` |
| `.gallery-item` | `.big` | `[data-size="big"]` |
| `.grade` | `.opt` | `[data-variant="optional"]` |
| `.heart` | `.on` | `[aria-pressed="true"]` |
| `.kinds` | `.row` | `[data-variant="row"]` |
| `.lightbox` | `.xg-lb` | `[data-variant="expedition"]` |
| `.lightbox-thumb` | `.on` | `[aria-current="true"]` |
| `.link` | `.see` | `.nw` |
| `.map` | `.small` | `[data-size="sm"]` |
| `.map-item` | `.k-focus` | `[data-kind="focus"]` |
| `.map-item` | `.k-here` | `[data-kind="here"]` |
| `.map-item` | `.k-near` | `[data-kind="near"]` |
| `.map-item` | `.k-off` | `[data-kind="off"]` |
| `.map-keyitem` | `.xk-road` | `[data-mode="road"]` |
| `.map-keyitem` | `.xk-water` | `[data-mode="water"]` |
| `.marker` | `.cur` | `[aria-current="true"]` |
| `.marker` | `.fut` | `[data-state="future"]` |
| `.marker` | `.hl` | `[data-state="hl"]` |
| `.mega-group` | `.c2` | `[data-columns="2"]` |
| `.mega-group` | `.c3` | `[data-columns="3"]` |
| `.mega-places` | `.wide` | `[data-variant="wide"]` |
| `.month` | `.good` | `[data-state="good"]` |
| `.month` | `.now` | `[aria-current="true"]` |
| `.months` | `.bars` | `[data-variant="bars"]` |
| `.nav-link` | `.cur` | `[aria-current="true"]` |
| `.option` | `.on` | `[aria-checked="true"]` |
| `.pagehead` | `.noimg` | `[data-variant="text"]` |
| `.pagehead` | `.wm` | `[data-variant="watermark"]` |
| `.pagehead-media` | `.mosaic` | `[data-variant="mosaic"]` |
| `.pill` | `.on` | `[aria-pressed="true"]` |
| `.pin` | `.k-focus` | `[data-kind="focus"]` |
| `.pin` | `.k-near` | `[data-kind="near"]` |
| `.pin` | `.k-off` | `[data-kind="off"]` |
| `.pin` | `.mk-near` | `[data-role="near"]` |
| `.pin-label` | `.at-b` | `[data-side="b"]` |
| `.pin-label` | `.at-l` | `[data-side="l"]` |
| `.pin-label` | `.at-r` | `[data-side="r"]` |
| `.pin-label` | `.at-t` | `[data-side="t"]` |
| `.pin-label` | `.off` | `[data-state="off"]` |
| `.place` | `.hit` | `[data-state="hit"]` |
| `.place-photo` | `.map` | `[data-variant="map"]` |
| `.placeholder` | `.big` | `[data-size="big"]` |
| `.price` | `.big` | `[data-size="lg"]` |
| `.price` | `.req` | `[data-variant="request"]` |
| `.region` | `.hit` | `[data-state="hit"]` |
| `.result-arrow` | `.next` | `[data-dir="next"]` |
| `.result-arrow` | `.prev` | `[data-dir="prev"]` |
| `.result-dot` | `.on` | `[aria-current="true"]` |
| `.save` | `.on` | `[aria-pressed="true"]` |
| `.scopes-tab` | `.on` | `[aria-selected="true"]` |
| `.search-small` | `.hcap` | `[data-variant="caption"]` |
| `.sec` | `.tight` | `[data-size="tight"]` |
| `.segmented` | `.rg-f` | `[data-variant="region"]` |
| `.steps` | `.three` | `[data-variant="three"]` |
| `.suggest-item` | `.on` | `[aria-selected="true"]` |
| `.tabs-link` | `.on` | `[aria-current="true"]` |
| `.tag` | `.mine` | `[data-variant="mine"]` |
| `.view` | `.no-fade` | `[data-motion="off"]` |
| `.voucher-mark` | `.on` | `[data-state="on"]` |

## Parts that were element selectors

| Inside (old) | Element | Gets class |
|---|---|---|
| `.ab` | `h3` | `.about-name` |
| `.ab` | `p` | `.about-text` |
| `.acc` | `span` | `.accordion-label` |
| `.acc` | `summary` | `.accordion-summary` |
| `.biz` | `h2` | `.business-heading` |
| `.biz-l` | `p` | `.business-text` |
| `.bk-ask` | `summary` | `.ask-summary` |
| `.bk-blk` | `h2` | `.checkout-heading` |
| `.bk-blk` | `i` | `.checkout-mark` |
| `.bk-cal-t` | `b` | `.calendar-bold` |
| `.bk-cal-t` | `em` | `.calendar-emph` |
| `.bk-co` | `h1` | `.checkout-title` |
| `.bk-co-dl` | `dd` | `.checkout-value` |
| `.bk-co-dl` | `div` | `.checkout-row` |
| `.bk-co-dl` | `dt` | `.checkout-term` |
| `.bk-d` | `span` | `.calendar-label` |
| `.bk-df` | `b` | `.datefield-bold` |
| `.bk-df` | `small` | `.datefield-note` |
| `.bk-df` | `span` | `.datefield-label` |
| `.bk-facts` | `a` | `.booking-link` |
| `.bk-facts` | `li` | `.booking-item` |
| `.bk-gp` | `b` | `.wallet-bold` |
| `.bk-hs-f` | `i` | `.-mark` |
| `.bk-hs-h` | `b` | `.-bold` |
| `.bk-hs-m` | `b` | `.-bold` |
| `.bk-key` | `span` | `.calendar-label` |
| `.bk-lab` | `small` | `.booking-note` |
| `.bk-li` | `b` | `.booking-bold` |
| `.bk-li` | `small` | `.booking-note` |
| `.bk-li` | `span` | `.booking-span` |
| `.bk-opt` | `b` | `.option-bold` |
| `.bk-opt` | `small` | `.option-note` |
| `.bk-opt` | `span` | `.option-label` |
| `.bk-opts` | `legend` | `.options-legend` |
| `.bk-otp` | `h3` | `.otp-name` |
| `.bk-otp` | `p` | `.otp-text` |
| `.bk-sent` | `b` | `.booking-bold` |
| `.bk-sent` | `span` | `.booking-span` |
| `.bk-st` | `output` | `.booking-output` |
| `.bk-trip-r` | `b` | `.trip-bold` |
| `.bk-trip-r` | `small` | `.trip-note` |
| `.bk-trip-r` | `span` | `.trip-label` |
| `.bk-tt` | `b` | `.booking-bold` |
| `.bk-tt` | `small` | `.booking-note` |
| `.bk-tt` | `span` | `.booking-span` |
| `.bk-vc-top` | `h2` | `.checkout-heading` |
| `.bk-vc-top` | `p` | `.checkout-text` |
| `.cr-cl` | `b` | `.credits-bold` |
| `.cr-cl` | `li` | `.credits-item` |
| `.cr-cp` | `h1` | `.credits-title` |
| `.cr-cs` | `h2` | `.credits-heading` |
| `.cr-si` | `a` | `.credits-link` |
| `.cr-si` | `li` | `.credits-item` |
| `.cr-si` | `span` | `.credits-label` |
| `.crumb` | `a` | `.crumbs-link` |
| `.crumb` | `li` | `.crumbs-item` |
| `.crumb` | `ol` | `.crumbs-list` |
| `.cstrip` | `a` | `.types-link` |
| `.cstrip` | `li` | `.types-item` |
| `.cstrip` | `span` | `.types-label` |
| `.cstrip` | `ul` | `.types-list` |
| `.ct` | `b` | `.kind-bold` |
| `.ct` | `small` | `.kind-note` |
| `.dt` | `h1` | `.headline-h1` |
| `.dt-bar` | `b` | `.bar-bold` |
| `.dt-bar` | `small` | `.bar-note` |
| `.dt-tabs` | `a` | `.tabs-link` |
| `.dt-tabs` | `ul` | `.tabs-list` |
| `.emp` | `p` | `.nothing-text` |
| `.empty` | `p` | `.empty-text` |
| `.facts` | `b` | `.facts-bold` |
| `.facts` | `li` | `.facts-item` |
| `.fg-t` | `span` | `.facet-label` |
| `.fo` | `i` | `.choice-count` |
| `.fo` | `span` | `.choice-label` |
| `.fs-b` | `i` | `.filters-count` |
| `.fs-hd` | `h2` | `.filters-heading` |
| `.ft` | `a` | `.footer-link` |
| `.ft-bl` | `span` | `.footer-label` |
| `.ft-ls` | `a` | `.footer-link` |
| `.ft-ls` | `i` | `.footer-mark` |
| `.ft-mega` | `div` | `.footer-row` |
| `.ft-s` | `b` | `.footer-bold` |
| `.gm-c` | `a` | `.map-link` |
| `.gm-cd` | `a` | `.mapcard-link` |
| `.gm-cd` | `h3` | `.mapcard-name` |
| `.gm-kk` | `i` | `.map-swatch` |
| `.gm-l` | `a` | `.map-link` |
| `.gm-l` | `li` | `.map-li` |
| `.gm-l` | `small` | `.map-small` |
| `.gm-pop` | `a` | `.popup-link` |
| `.gm-pop` | `span` | `.popup-label` |
| `.grid` | `p` | `.grid-text` |
| `.hero` | `em` | `.hero-emph` |
| `.hero` | `h1` | `.hero-title` |
| `.hl` | `li` | `.checks-item` |
| `.hl` | `span` | `.checks-label` |
| `.how` | `b` | `.steps-bold` |
| `.how` | `li` | `.steps-item` |
| `.how` | `span` | `.steps-label` |
| `.how` | `strong` | `.steps-strong` |
| `.how-i` | `b` | `.steps-bold` |
| `.lb-f` | `b` | `.lightbox-bold` |
| `.lb-f` | `figcaption` | `.lightbox-caption` |
| `.lb-f` | `span` | `.lightbox-span` |
| `.lb-th` | `button` | `.lightbox-thumb` |
| `.lc4` | `a` | `.activity-a` |
| `.lc4` | `b` | `.activity-bold` |
| `.lc4` | `h3` | `.activity-name` |
| `.lc4` | `span` | `.activity-label` |
| `.lc4-m` | `span` | `.activity-label` |
| `.ldl` | `a` | `.deflist-link` |
| `.ldl` | `dd` | `.deflist-value` |
| `.ldl` | `div` | `.deflist-row` |
| `.ldl` | `dt` | `.deflist-term` |
| `.lgo-p` | `b` | `.logo-bold` |
| `.lh-m` | `a` | `.headline-link` |
| `.lh-m` | `span` | `.headline-label` |
| `.lm-v` | `b` | `.lang-bold` |
| `.loc` | `b` | `.location-bold` |
| `.loc` | `small` | `.location-note` |
| `.loc` | `span` | `.location-label` |
| `.lsec` | `h2` | `.detail-heading` |
| `.lside` | `h2` | `.aside-heading` |
| `.lside` | `p` | `.aside-text` |
| `.lside` | `small` | `.aside-note` |
| `.menu-b` | `span` | `.header-line` |
| `.mk` | `i` | `.pin-inner` |
| `.mk-lb` | `i` | `.pin-glyph` |
| `.mm-p` | `b` | `.mega-bold` |
| `.mm-p` | `small` | `.mega-note` |
| `.mm-p` | `span` | `.mega-label` |
| `.mob-r` | `i` | `.drawer-count` |
| `.mob-r` | `span` | `.drawer-label` |
| `.nav` | `ul` | `.nav-list` |
| `.pair-h` | `b` | `.pair-bold` |
| `.pair-h` | `span` | `.pair-label` |
| `.pc-t` | `b` | `.region-bold` |
| `.pc-t` | `small` | `.region-note` |
| `.ph` | `h1` | `.pagehead-title` |
| `.ph0` | `small` | `.placeholder-note` |
| `.pill` | `i` | `.pill-count` |
| `.pill` | `span` | `.pill-label` |
| `.plr` | `a` | `.place-link` |
| `.plr` | `h2` | `.place-heading` |
| `.plr-e` | `small` | `.place-note` |
| `.plr-g` | `span` | `.place-label` |
| `.plr-h` | `a` | `.place-link` |
| `.plr-h` | `span` | `.place-label` |
| `.plr-l` | `li` | `.place-item` |
| `.plr-ph` | `a` | `.place-link` |
| `.plr-pt` | `b` | `.place-bold` |
| `.plr-pt` | `small` | `.place-note` |
| `.plr-pv` | `b` | `.place-bold` |
| `.pr` | `b` | `.price-bold` |
| `.pr` | `small` | `.price-note` |
| `.promise-t` | `b` | `.promise-bold` |
| `.promise-t` | `span` | `.promise-label` |
| `.psec` | `h2` | `.plan-heading` |
| `.psec` | `p` | `.plan-text` |
| `.rc` | `a` | `.result-link` |
| `.rc` | `h3` | `.result-name` |
| `.rc-a` | `span` | `.result-label` |
| `.rc-d` | `i` | `.result-dot` |
| `.sea` | `h3` | `.season-name` |
| `.sea` | `p` | `.season-text` |
| `.sg` | `b` | `.suggest-bold` |
| `.sg` | `small` | `.suggest-note` |
| `.sg-none` | `a` | `.suggest-link` |
| `.sgo` | `span` | `.search-label` |
| `.sh` | `h2` | `.sechead-heading` |
| `.sh` | `p` | `.sechead-text` |
| `.smd` | `a` | `.twodays-link` |
| `.smd` | `b` | `.twodays-bold` |
| `.smd-day` | `a` | `.twodays-link` |
| `.smd-day` | `b` | `.twodays-bold` |
| `.smd-m` | `span` | `.twodays-label` |
| `.smsg` | `a` | `.search-link` |
| `.sp-h` | `b` | `.sheet-bold` |
| `.srcs` | `a` | `.sources-link` |
| `.srcs` | `b` | `.sources-bold` |
| `.srcs` | `small` | `.sources-note` |
| `.st` | `b` | `.month-bold` |
| `.st` | `small` | `.month-note` |
| `.st-c` | `i` | `.month-mark` |
| `.stay` | `b` | `.stay-bold` |
| `.stay` | `span` | `.stay-label` |
| `.strip-k` | `span` | `.months-label` |
| `.sv` | `i` | `.saved-count` |
| `.sv-p` | `a` | `.saved-link` |
| `.sv-p` | `b` | `.saved-bold` |
| `.sv-p` | `h3` | `.saved-name` |
| `.sv-p` | `li` | `.saved-item` |
| `.sv-p` | `p` | `.saved-text` |
| `.sv-p` | `small` | `.saved-note` |
| `.sv-p` | `ul` | `.saved-list` |
| `.ta-acc` | `summary` | `.days-summary` |
| `.ta-df` | `b` | `.days-bold` |
| `.ta-df` | `span` | `.days-label` |
| `.ta-grp` | `small` | `.days-note` |
| `.ta-grp` | `summary` | `.days-summary` |
| `.ta-hd` | `h3` | `.day-name` |
| `.ta-hl` | `b` | `.days-bold` |
| `.ta-ph` | `button` | `.days-button` |
| `.ta-st` | `b` | `.days-bold` |
| `.ta-st` | `small` | `.days-note` |
| `.ta-tl` | `li` | `.days-item` |
| `.ta-tli` | `b` | `.days-bold` |
| `.ta-tli` | `summary` | `.days-summary` |
| `.toc` | `a` | `.toc-link` |
| `.toc` | `ul` | `.toc-list` |
| `.v5` | `small` | `.pass-note` |
| `.v5-f` | `b` | `.pass-bold` |
| `.v5-f` | `p` | `.pass-text` |
| `.v5-foot` | `b` | `.pass-bold` |
| `.v5-foot` | `p` | `.pass-text` |
| `.v5-foot` | `small` | `.pass-note` |
| `.v5-foot` | `span` | `.pass-label` |
| `.vc` | `h3` | `.voucher-name` |
| `.vc` | `p` | `.voucher-text` |
| `.xcap` | `i` | `.capacity-seat` |
| `.xe2-h` | `a` | `.expedition-link` |
| `.xe2-m` | `span` | `.expedition-label` |
| `.xres` | `a` | `.resultsbar-link` |

## Elements that keep a tag

| Element | Class | Gets |
|---|---|---|
| `a` | `.xe2-ph` | `data-as="a"` |
| `button` | `.lnk` | `data-as="button"` |
| `legend` | `.fg-h` | `data-as="legend"` |
| `ol` | `.how` | `data-as="ol"` |

## Ids

The sheet no longer selects ids; the element gets a class.

| Id | Class |
|---|---|
| `#hd` | `.header` |
| `#smd-ask` | `.twodays-ask` |
| `#bk-both` | `.booking-both` |

## Dropped

Dead classes (no page, script or generator uses them), the hosted-payment placeholder `.bk-hs*` and the review-only marks `.bk-dsg*`: their rules are not ported.

`.bar-u`, `.biz-h`, `.bk-dsg`, `.bk-dsg-b`, `.bk-hs`, `.bk-hs-b`, `.bk-hs-f`, `.bk-hs-h`, `.bk-hs-m`, `.bk-hs-pay`, `.bk-hs-r`, `.exp`, `.fbar`, `.flash`, `.four-x`, `.g3`, `.hd-lm`, `.hi`, `.how-g`, `.how3`, `.j1`, `.join`, `.join-i`, `.lc4-f`, `.lg-m`, `.lg-s`, `.lgal`, `.lgo-mini`, `.lh`, `.lh-t`, `.lm-s`, `.lmeta`, `.lmeta-l`, `.lnb`, `.mine`, `.mo-1`, `.mo-2`, `.mo-3`, `.mo-go`, `.mo-t`, `.mob-ps`, `.mob-sv`, `.mob-x`, `.mon-k`, `.op`, `.op-do`, `.op-note`, `.opb`, `.own`, `.own-i`, `.own-l`, `.own-p`, `.own-tag`, `.pal-h`, `.pal-menu`, `.pal-n`, `.pal-sw`, `.ph-x`, `.qlist`, `.qvb`, `.sm-k`, `.sres`, `.stitch`, `.tier`, `.tiers`, `.tiers3`, `.trial`, `.trial-k`, `.trial-logo`, `.trial-t`, `.who`, `.who-c`, `.who-g`, `.word-t`, `.xc`, `.xc-c`, `.xc-i`, `.xc-t`

## JavaScript changes for slice 0b

What the engine's scripts toggle today and must set instead (the class names come from the tables above):

- **Pressed and selected**: `.pill.on`, `.fo.on` (filter options), `.heart.on`, `.save.on`, `.bk-d.on` → `aria-pressed="true"|"false"`; `.scope.on`, `.sg.on` (suggestion) → `aria-selected`; `.bk-opt.on` → `aria-checked` (radio cards).
- **Current**: `.dt-tabs a.on`, `.nav-a.cur`, `.mob-r.cur`, `.acc.cur`, `.lb-th button.on`, `.rc-d i.on`, `.gm-m.cur`, `.st.now` → `aria-current="true"`.
- **Opened day**: stop toggling `.ta-rc.on`; set `aria-expanded` on the day's toggle (`.day-toggle`).
- **Facets**: `.fg.shut` → `data-state="shut"`, `.fg.all` → `data-state="all"`; `.fo.zero` → `data-count="0"`; `.fo.more` → `data-variant="more"`.
- **Map**: labels `at-r|l|t|b` → `data-side="r|l|t|b"`, `.off` → `data-state="off"`; markers `k-${kind}` → `data-kind`, `mk-${role}` → `data-role`, `.hl` → `data-state="hl"`, `.fut` → `data-state="future"`; `.nomap` → `data-map="none"`; `.gmap.small` → `data-size="sm"`.
- **Booking**: `.bk-df.set` → `data-state="set"`, `.bk-df.bad` → `aria-invalid="true"`, `.bk-cal.bad` → `data-state="invalid"`, `.bk-d.rng|dep|full` → `data-state="range|departure|full"`, `.vc-mark.on` → `data-state="on"`, `.bk-on`/`.bk-por` on the page → `data-booking="on"` / `data-booking-portrait`.
- **Page states on `<html>`/`<body>`**: `.locked` → `data-locked`, `.has-bar` → `data-bar`, `.scrolled` → `data-scrolled`, `.hd-up` → `data-header="up"`, `.fs-open` → `data-filters="open"`.
- **Highlights**: `.pc.hit`, `.plr.hit` → `data-state="hit"`; `.xe-card.on`, `.xe2.on`, `.xcap i.on` → `data-state="on"`.
- **Styles**: the language spans keep `.en` / `.sw`; the palette and logo switch stay `data-palette` / `data-logo` on `<html>`. Element queries in scripts (`$('.lc4 h3')`) work unchanged, but new markup should add the part classes.

