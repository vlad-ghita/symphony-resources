<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:import href="../../../widgets/master/xsl/master.xsl"/>

<xsl:template match="data">
	<xsl:apply-templates select="homepage-articles/entry"/>
	<xsl:apply-templates select="notes"/>
</xsl:template>

</xsl:stylesheet>
