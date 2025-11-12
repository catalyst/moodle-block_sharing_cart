import $ from 'jquery';
import SharingCartForSnap from 'theme_snap/sharing_cart';

// We don't import block_sharing_cart/script because it's written as globals.
// Instead, we assume $.on_backup and $.on_section_backup are defined
// once block_sharing_cart/script has been loaded.

export const init = function(params) {
    require(['block_sharing_cart/script'], function() {
        if (typeof $.on_backup !== 'function' || typeof $.on_section_backup !== 'function') {
            return;
        }

        const scSnap = new SharingCartForSnap(params);
        window.snapSharingCart = new SharingCartForSnap(params);

        scSnap.snapFix({
            course: params.course,
            iconBackup: params.iconBackup,
            on_backup: $.on_backup,
            on_section_backup: $.on_section_backup,
            courseSections:params.courseSections,
            sectionsjs:params.sectionsjs,
            lazy:params.lazy,
        });
    });
};
