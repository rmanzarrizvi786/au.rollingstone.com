export default class LineFrame {
	constructor ( el ) {
		this.el = el;
		this.setLineLength();
	}

	setLineLength () {
		const rect = this.el.getBoundingClientRect();
		const lineLength = rect.width * 2 + rect.height * 2;
		this.el.setAttribute( 'stroke-dashoffset', lineLength );
		this.el.setAttribute( 'stroke-dasharray', lineLength );
	}
}
