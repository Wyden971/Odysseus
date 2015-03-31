<?php

namespace Odysseus\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OdysseusAdminBundle extends Bundle
{
	
	public function getParent()
    {
        return 'FOSUserBundle';
    }
	
}
