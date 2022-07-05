;(function($) {
	// プラグインの定義 - オーバーライド
	$.widget('ui.simpleDialog', $.ui.dialog, {

        _createOverlay: function () {
            if (!this.options.modal) {
              return;
            }
      
            // We use a delay in case the overlay is created from an
            // event that we're going to be cancelling (#2804)
            var isOpening = true;
            this._delay(function () {
              isOpening = false;
            });
      
            // if (!this.document.data('ui-dialog-overlays')) {
            //   // Prevent use of anchors and inputs
            //   // Using _on() for an event handler shared across many instances is
            //   // safe because the dialogs stack and must be closed in reverse order
            //   this._on(this.document, {
            //     focusin: function (event) {
            //       if (isOpening) {
            //         return;
            //       }
      
            //       if (!this._allowInteraction(event)) {
            //         event.preventDefault();
            //         this._trackingInstances()[0]._focusTabbable();
            //       }
            //     }
            //   });
            // }
      
            this.overlay = $('<div>').appendTo(this._appendTo());
      
            this._addClass(this.overlay, null, 'ui-widget-overlay ui-front');
            // this._on(this.overlay, {
            //   mousedown: '_keepFocus'
            // });
            this.document.data(
              'ui-dialog-overlays',
              (this.document.data('ui-dialog-overlays') || 0) + 1
            );
        },
      
        _destroyOverlay: function () {
            if (!this.options.modal) {
              return;
            }
      
            if (this.overlay) {
              var overlays = this.document.data('ui-dialog-overlays') - 1;
      
              if (!overlays) {
                this._off(this.document, 'focusin');
                this.document.removeData('ui-dialog-overlays');
              } else {
                this.document.data('ui-dialog-overlays', overlays);
              }
      
              this.overlay.remove();
              this.overlay = null;
            }
        }
	});
})(jQuery);