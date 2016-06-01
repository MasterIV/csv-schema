<?php

namespace Iv\Csv\Schema;

interface Node
{
	public function readLine( $line, &$parent, $last = false );
}
