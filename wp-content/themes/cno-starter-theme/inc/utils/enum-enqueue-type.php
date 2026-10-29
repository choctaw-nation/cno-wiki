<?php
/**
 * Describes the allowable Enqueue types
 *
 * @package ChoctawNation
 */

namespace ChoctawNation\Utils;

/** Allowable Enqueue Types */
enum Enqueue_Type {
	case script;
	case style;
	case both;
}
