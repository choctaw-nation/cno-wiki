import { newSlider } from './swiper';

const swiper = document.querySelector< HTMLElement >( '.swiper' );
if ( swiper ) {
	newSlider( swiper, {
		breakpoints: {
			767: {
				slidesPerView: 2,
				slidesPerGroup: 2,
			},
			991: {
				slidesPerView: 2,
				slidesPerGroup: 2,
			},
		},
	} );
}
