/*
 * This file is part of the Soapbox Race World core source code.
 * If you use any of this code for third-party purposes, please provide attribution.
 * Copyright (c) 2020.
 */

package com.soapboxrace.core.bo.util;

import com.soapboxrace.core.jpa.ProductEntity;

public class ItemRewardQuantityProduct extends ItemRewardProduct {
    private final Integer useCount;

    public ItemRewardQuantityProduct(ProductEntity productEntity) {
        super(productEntity);
        this.useCount = -1;
    }

    public ItemRewardQuantityProduct(ProductEntity productEntity, Integer useCount) {
        super(productEntity);
        this.useCount = useCount;
    }

    public Integer getUseCount() {
        return useCount;
    }
}
